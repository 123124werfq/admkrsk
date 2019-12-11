<?php

namespace backend\controllers;

use Yii;

use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use common\models\Service;
use common\models\Form;
use common\models\Action;
use common\modules\log\models\Log;
use backend\models\search\ProfileSearch;

class ReserveController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }


    public function actionProfile()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}
