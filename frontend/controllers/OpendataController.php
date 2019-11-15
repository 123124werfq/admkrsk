<?php

namespace frontend\controllers;

use common\models\Opendata;
use common\models\Page;
use frontend\models\search\OpendataSearch;
use Yii;
use yii\web\NotFoundHttpException;

class OpendataController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new OpendataSearch();
        $dataProvider = $searchModel->search([]);

        if (($page = Page::findOne(['alias' => 'opendata'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    public function actionView($id)
    {
        $model = Opendata::findOne(['identifier' => $id]);

        if (empty($model)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (($page = Page::findOne(['alias' => 'opendata'])) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->createAction();

        return $this->render('view',[
            'model'=>$model,
            'page' => $page,
        ]);
    }
}
