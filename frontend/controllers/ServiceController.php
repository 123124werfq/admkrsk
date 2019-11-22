<?php

namespace frontend\controllers;

use common\models\Service;
use common\models\Form;
use common\models\ServiceRubric;
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

    public function actionReestr($page=null)
    {
        $clientType = (int)Yii::$app->request->get('client_type');

        $online = (int)Yii::$app->request->get('online');

        if (!empty($clientType) || !empty($online))
        {
            $services = Service::find();

            if (!empty($clientType))
                $services->andWhere('client_type&',$clientType.'='.$clientType);

            if (!empty($online))
                $services->andWhere(['online'=>1]);

            $services = $services->all();

            return $this->renderPartial('_table',['services'=>$services]);
        }

        $rubrics = ServiceRubric::find()->with('childs')->where('id_parent IS NULL')->all();

        return $this->render('reestr',[
            'page'=>$page,
            'rubrics'=>$rubrics,
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

    public function actionCreate($id,$page=null)
    {
        $service = $this->findModel($id);

        if (empty($service->id_form))
            throw new NotFoundHttpException('Такой страницы не существует');

        $model = new FormDynamic($service->form);

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
}
