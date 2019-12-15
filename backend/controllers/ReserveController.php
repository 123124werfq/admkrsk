<?php

namespace backend\controllers;

use common\models\HrContest;
use common\models\HrExpert;
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
use backend\models\search\ContestSearch;
use backend\models\search\ExpertSearch;
use backend\models\forms\ExpertForm;

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

    public function actionContest()
    {
        $searchModel = new ContestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('contests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $contest = new HrContest;

        return $this->render('create', [
            'model' => $contest
        ]);
    }

    public function actionUpdate($id)
    {

    }

    public function actionExperts()
    {
        $searchModel = new ExpertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $emodel = new HrExpert;
        $expertForm = new ExpertForm;
        $assign = false;

        return $this->render('experts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $emodel,
            'expertForm' => $expertForm,
            'assign' => $assign,
        ]);
    }

    public function actionList()
    {

    }




}
