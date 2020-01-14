<?php

namespace backend\controllers;

use common\models\CollectionRecord;
use common\models\GridSetting;
use common\models\HrContest;
use common\models\HrExpert;
use common\models\HrProfile;
use common\models\HrProfilePositions;
use common\models\HrReserve;
use common\models\HrResult;
use common\models\HrVote;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\search\ProfileSearch;
use backend\models\search\ContestSearch;
use backend\models\search\ExpertSearch;
use backend\models\forms\ExpertForm;
use backend\models\forms\ContestForm;

use common\models\CollectionColumn;

class ReserveController extends Controller
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
        $searchModel = new ProfileSearch();
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

    public function actionContest()
    {
        $searchModel = new ContestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::gridContest,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('contests', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
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

            $sql = "DELETE FROM hrl_contest_expert WHERE id_contest = {$model->id_contest}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($_POST['HrContest']['experts'] as $id_expert) {
                $sql = "INSERT INTO hrl_contest_expert (id_contest, id_expert) VALUES ({$model->id_contest}, {$id_expert})";
                Yii::$app->db->createCommand($sql)->execute();
            }

            $sql = "DELETE FROM hrl_contest_profile WHERE id_contest = {$model->id_contest}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($_POST['HrContest']['profiles'] as $id_profile) {
                $sql = "INSERT INTO hrl_contest_profile (id_contest, id_profile) VALUES ({$model->id_contest}, {$id_profile})";
                Yii::$app->db->createCommand($sql)->execute();
            }

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
        $grid = GridSetting::findOne([
            'class' => static::gridExperts,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        $emodel = new HrExpert;
        $expertForm = new ExpertForm;
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
        $model = new ExpertForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->promote();
        }

        return $this->redirect('/reserve/experts');
    }

    public function actionDismiss()
    {
        $expert = HrExpert::findOne((int)$_GET['id']);

        if ($expert)
            $expert->delete();

        return $this->redirect('/reserve/experts');
    }

    public function actionList()
    {
        $query = HrReserve::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['contest_date' => SORT_DESC]]
        ]);
        $grid = GridSetting::findOne([
            'class' => static::gridList,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }

    public function actionArchived()
    {
        $query = HrProfile::find()->where(['state' => HrProfile::STATE_ARCHIVED]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id_profile' => SORT_DESC]]
        ]);
        $grid = GridSetting::findOne([
            'class' => static::gridArchive,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('archive', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }

    public function actionView($id)
    {
        $profile = HrProfile::findOne($id);

        $record = CollectionRecord::findOne($profile->id_record);

        if (empty($record))
            throw new NotFoundHttpException('Ошибка чтения данных');

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

    public function actionBan($id)
    {
        $profile = HrProfile::findOne($id);

        if (empty($profile))
            throw new NotFoundHttpException('Ошибка чтения данных');

        if ($profile->isBusy())
            return $this->redirect('/reserve/profile');

        if ($profile->state == HrProfile::STATE_ACTIVE)
            $profile->state = HrProfile::STATE_BANNED;
        else if ($profile->state == HrProfile::STATE_BANNED)
            $profile->state = HrProfile::STATE_ACTIVE;

        $profile->updateAttributes(['state']);

        return $this->redirect('/reserve/profile');
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

        return $this->redirect('/collection-record/update?id=' . $profile->id_record);
    }

    public function actionStop($id)
    {
        $contest = HrContest::findOne($id);

        if (empty($contest))
            throw new NotFoundHttpException('Ошибка чтения данных');

        $contest->state = HrContest::STATE_CLOSED;
        $contest->end = time() - 60;

        $contest->updateAttributes(['state', 'end']);

        return $this->redirect('/reserve/contest');

    }

    public function actionDynamic($id = 0)
    {
        $votes = [];

        if (!$id)
            $contest = HrContest::active();
        else
            $contest = HrContest::findOne($id);

        if ($contest)
            $votes = HrVote::find()->where(['id_contest' => $contest->id_contest])->all();

        if (!empty($_POST['results'])) {
//            print_r($_POST); die();
            foreach ($_POST['results'] as $id_profile => $resultVotes) {
                $profileItem = HrProfile::findOne($id_profile);
                $profileItem->reserve_date = null;
                $profileItem->state = HrProfile::STATE_ACTIVE;
                $profileItem->updateAttributes(['reserve_date', 'state']);

                foreach ($resultVotes as $idProfilePosition => $result) {
                    $profilePosition = HrProfilePositions::findOne($idProfilePosition);

                    $posResult = HrResult::find()->where(['id_profile' => $id_profile, 'id_contest' => $contest->id_contest, 'id_record' => $profilePosition->id_record_position])->one();

                    if (!$posResult) {
                        $posResult = new HrResult;
                        $posResult->id_profile = $id_profile;
                        $posResult->id_contest = $contest->id_contest;
                        $posResult->id_record = $profilePosition->id_record_position;
                        $posResult->save();
                    }

                    $posResult->result = $result;
                    $posResult->updateAttributes(['result']);

                    // включаем в резерв
                    if ($result == 1) {
                        $reserveItem = HrReserve::find()->where(['id_profile' => $id_profile, 'id_result' => $posResult->id_result])->one();

                        if (!$reserveItem) {
                            $reserveItem = new HrReserve;
                            $reserveItem->id_profile = $id_profile;
                            $reserveItem->id_result = $posResult->id_result;
                            $reserveItem->id_record_position = $profilePosition->id_record_position;
                            $reserveItem->contest_date = $contest->end;
                            $reserveItem->save();
                        }

                        $profileItem->reserve_date = $contest->end;
                        $profileItem->state = HrProfile::STATE_RESERVED;
                        $profileItem->updateAttributes(['reserve_date', 'state']);
                    }

                    $profilePosition->id_result = $posResult->id_result;
                    $profilePosition->updateAttributes(['id_result']);
                }
            }
            $contest->state = HrContest::STATE_FINISHED;
            $contest->updateAttributes(['state']);
        }

        return $this->render('dynamic', [
            'data' => $contest,
            'votes' => $votes
        ]);

    }


    public function actionUnreserve($id)
    {
        $reserveItem = HrReserve::findOne($id);

        if ($reserveItem)
            $reserveItem->delete();

        return $this->redirect('/reserve/list');
    }

    public function actionSpreadsheet($id)
    {
        $votes = [];
        $contest = HrContest::findOne($id);

        if ($contest)
            $votes = HrVote::find()->where(['id_contest' => $contest->id_contest])->all();
        else
            Yii::$app->end();

        header('Content-type: application/excel');
        header('Content-Disposition: attachment; filename=Итоги голосования ' . date('d-m-Y H:i', $contest->begin) . ' - ' . date('d-m-Y H:i', $contest->end) . '.xls');

        $body = $this->renderPartial('dynamic_excel', [
            'data' => $contest,
            'votes' => $votes
        ]);

        //echo iconv( "utf-8", "windows-1251",$body);
        echo $body;

        Yii::$app->end();
    }


    /*
    public function actionArchived()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReserved()
    {
        $searchModel = new ProfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('profile', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    */


}
