<?php

namespace backend\controllers;

use common\models\CollectionRecord;
use common\models\HrContest;
use common\models\HrExpert;
use common\models\HrProfile;
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
use backend\models\forms\ContestForm;

use common\models\CollectionColumn;

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
        $contest = new ContestForm;

        if ($contest->load(Yii::$app->request->post())) {
            $contest->create();
            return $this->redirect('/reserve/contest');
        }

        return $this->render('create', [
            'model' => $contest
        ]);
    }

    public function actionEdit($id)
    {

        $model = HrContest::findOne($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect('/reserve/contest');
        }

        return $this->render('update', [
            'model' => $model,
        ]);


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

    public function actionPromote()
    {
        $model = new ExpertForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->promote();
        }

        return $this->redirect('/reserve/experts');
    }

    public function actionDismiss()
    {
        $expert = HrExpert::findOne((int)$_GET['id']);

        if($expert)
            $expert->delete();

        return $this->redirect('/reserve/experts');
    }

    public function actionList()
    {

    }

    public function actionView($id)
    {
        $profile = HrProfile::findOne($id);

        $record = CollectionRecord::findOne($profile->id_record);

        if (empty($record))
            throw new NotFoundHttpException('Ошибка чтения данных');

        $insertedData = $record->getData();

        $columns = CollectionColumn::find()->where(['id_collection' => $record->id_collection])->indexBy('id_column')->orderBy('ord')->all();

        foreach ($insertedData as $rkey => $ritem)
        {
            $formFields[$columns[$rkey]->alias] = ['value' => empty($ritem)?"[не заполнено]":$ritem, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
        }

        usort($formFields, function($a, $b){return ($a['ord']<$b["ord"])?-1:1;});

        $attachments = $record->getAllMedias();

        return $this->render('viewprofile', [
            'model' => $profile,
            'record' => $record,
            'formFields' => $formFields,
            'attachments' => $attachments
        ]);
    }

    public function actionBan($id)
    {
        $profile = HrProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        if($profile->state == HrProfile::STATE_ACTIVE)
            $profile->state = HrProfile::STATE_BANNED;
        else if($profile->state == HrProfile::STATE_BANNED)
            $profile->state = HrProfile::STATE_ACTIVE;

        $profile->updateAttributes(['state']);

        $this->redirect('/reserve/profile');
    }

    public function actionArchive($id)
    {
        $profile = HrProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        $profile->state = HrProfile::STATE_ARCHIVED;

        $profile->updateAttributes(['state']);

    }

    public function actionEditable($id)
    {
        $profile = HrProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        return $this->redirect('/collection-record/update?id='.$profile->id_record);
    }


}
