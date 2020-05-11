<?php

namespace frontend\controllers;

use common\models\Opendata;
use common\models\Page;
use frontend\models\search\OpendataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OpendataController extends Controller
{
    /**
     * @return string
     * @throws NotFoundHttpException
     */
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

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        if (($model = Opendata::findOne(['identifier' => $id])) === null) {
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
