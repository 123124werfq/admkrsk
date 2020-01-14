<?php

namespace backend\controllers;

use Yii;
use backend\models\search\FiasUpdateHistorySearch;
use yii\web\Controller;

/**
 * FiasUpdateHistoryController implements the CRUD actions for FiasUpdateHistory model.
 */
class FiasUpdateHistoryController extends Controller
{
    /**
     * Lists all FiasUpdateHistory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FiasUpdateHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
