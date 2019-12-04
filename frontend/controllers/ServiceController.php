<?php

namespace frontend\controllers;

use common\models\Service;
use common\models\Form;
use common\models\ServiceRubric;
use common\models\ServiceSituation;
use common\models\ServiceTarget;
use common\models\FormDynamic;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

class ServiceController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create'],
                'rules' => [
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($page=null)
    {
        return $this->render('/site/blocks',['page'=>$page]);
    }

    public function actionReestr($page=null,$id_situation=null)
    {
        $clientType = (int)Yii::$app->request->get('client_type');
        $online = (int)Yii::$app->request->get('online');

        $services = Service::find()->where(['old'=>0]);

        $open = false;

        if (!empty($clientType) || !empty($online))
        {
            if (!empty($clientType))
            {
                $open = true;
                $services->andWhere('client_type&',$clientType.'='.$clientType);
            }

            if (!empty($online))
            {
                $open = true;
                $services->andWhere(['online'=>1]);
            }
        }

        if (!empty($id_situation))
        {
            $id_situation = (int)$id_situation;
            $open = true;
            $services->andWhere("id_service IN (SELECT id_service FROM servicel_situation WHERE id_situation = $id_situation)");
        }

        $servicesRubs = [];

        foreach ($services->all() as $key => $data)
            $servicesRubs[(int)$data->id_rub][$data->id_service] = $data;

        $rubrics = ServiceRubric::find()->with(['childs','parent'])->where(['id_rub'=>array_keys($servicesRubs)])->all();

        $tree = [];

        foreach ($rubrics as $key => $rub)
        {
            $tree[(int)$rub->id_parent][$rub->id_rub] = $rub;

            if (!empty($rub->id_parent))
            {
                $tree[(int)$rub->parent->id_parent][$rub->id_parent] = $rub->parent;

                if (!empty($rub->parent->id_parent))
                {
                    $tree[(int)$rub->parent->parent->id_parent][$rub->parent->parent->id_parent] = $rub->parent->parent;
                }
            }
        }

        return $this->render('reestr',[
            'page'=>$page,
            'servicesRubs'=>$servicesRubs,
            'rubrics'=>$tree,
            'open'=>$open,
        ]);
    }

    public function actionView($id,$page=null)
    {
    	$model = $this->findModel($id);

        $model->createAction();

        return $this->render('view',[
        	'service'=>$model,
            'page'=>$page,
        ]);
    }

    public function actionCreate($id,$id_target,$page=null)
    {
        $inputs = [];

        

        $target = $this->findTarget($id_target);
        $service = $target->service;
        $form = $target->form;
        $inputs['id_service'] = $service->id_service;
        $inputs['id_target'] = $target->id_target;

        if (empty($form))
            throw new NotFoundHttpException('Такой страницы не существует');

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData();
            print_r($prepare);

            /*print_r($_FILES);
            print_r($model->attributes);*/
            die();

            return $this->redirect('/service-recieved');
        }

        return $this->render('create',[
            'form'=>$model,
            'service'=>$service,
            'page'=>$page,
            'inputs'=>$inputs,
        ]);
    }

    public function actionSearch()
    {
        $search = Yii::$app->request->get('term');

        if (empty($search))
            Yii::$app->end();

        $records = Service::find()->where(['like','name',$search])->limit(10)->all();

        $output = [];

        foreach ($records as $data)
            $output[] = ['id'=>$data->id_service, 'value'=>$data->name, 'label'=>$data->name, 'description'=>'', 'redirect'=>$data->getUrl()];

        return json_encode($output);
    }

    protected function findModel($id)
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }

    protected function findTarget($id)
    {
        if (($model = ServiceTarget::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }
}
