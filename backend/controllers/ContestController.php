<?php

namespace backend\controllers;

use common\models\CollectionRecord;
use common\models\GridSetting;
use common\models\CstExpert;
use common\models\CstProfile;
use common\models\CstResult;
use common\models\CstVote;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\search\CprofileSearch;
use backend\models\search\CexpertSearch;
use backend\models\forms\CstExpertForm;

use common\models\CollectionColumn;

class ContestController extends Controller
{
    const gridProfile = 'profile-grid';
    const gridContest = 'contest-grid';
    const gridExperts = 'experts-grid';
    const gridList = 'list-grid';
    const gridArchive = 'archive-grid';

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionProfile()
    {
        $searchModel = new CprofileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::gridProfile,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }

    public function actionView($id)
    {
        $profile = CstProfile::findOne($id);

        $record = CollectionRecord::findOne($profile->id_record_anketa);

        if (empty($record))
            throw new NotFoundHttpException('Ошибка чтения данных');

        if(Yii::$app->request->get('_csrf'))
        {
            $profile->comment = Yii::$app->request->get('comment');
            $profile->updateAttributes(['comment']);

            return $this->redirect('/contest/profile');
        }

        $insertedData = $record->getData();

        $columns = CollectionColumn::find()->where(['id_collection' => $record->id_collection])->indexBy('id_column')->orderBy('ord')->all();

        foreach ($insertedData as $rkey => $ritem) {
            $formFields[$columns[$rkey]->alias] = ['value' => empty($ritem) ? "[не заполнено]" : $ritem, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
        }

        usort($formFields, function ($a, $b) {
            return ($a['ord'] < $b["ord"]) ? -1 : 1;
        });

        $attachments = $record->getAllMedias();

        return $this->render('viewprofile', [
            'model' => $profile,
            'record' => $record,
            'formFields' => $formFields,
            'attachments' => $attachments
        ]);
    }

    public function actionEditable($id)
    {
        $profile = CstProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        return $this->redirect('/collection-record/update?id=' . $profile->id_record_anketa);
    }

    public function  actionStatus($id)
    {
        $profile = CstProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        switch ($profile->state) {
            case CstProfile::STATE_DRAFT:
                $profile->state = CstProfile::STATE_ACCEPTED;
                break;
            case CstProfile::STATE_ACCEPTED:
                $profile->state = CstProfile::STATE_DRAFT;
                break;
        }

        $profile->updateAttributes(['state']);

        return $this->redirect('/contest/profile');
    }


    public function actionExperts()
    {
        $searchModel = new CexpertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::gridExperts,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        $emodel = new CstExpert;
        $expertForm = new CstExpertForm;
        $assign = false;

        return $this->render('experts', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $emodel,
            'expertForm' => $expertForm,
            'assign' => $assign,
            'customColumns' => $columns,
        ]);
    } 

    public function actionPromote()
    {
        $model = new CstExpertForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->promote();
        }

        return $this->redirect('/contest/experts');
    }

    public function actionDismiss()
    {
        $expert = CstExpert::findOne((int)$_GET['id']);

        if ($expert)
            $expert->delete();

        return $this->redirect('/contest/experts');
    }    

}