<?php

namespace frontend\controllers;
use common\models\Service;
use common\models\Form;
use common\models\ServiceRubric;
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
        return $this->render('/site/page',['page'=>$page]);
    }

    public function actionReestr($page=null)
    {
        $rubrics = ServiceRubric::find()->with('childs')->where('id_parent IS NULL')->all();

        return $this->render('reestr',[
            'page'=>$page,
            'rubrics'=>$rubrics,
        ]);
    }

    public function actionView($id,$page=null)
    {
    	$model = $this->findModel($id);

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

        $model = new Form;

        if (!empty($_POST))//$model->load(Yii::$app->request->post()) && $model->validate()
        {
            print_r($_POST);
            print_r($_FILES);

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
