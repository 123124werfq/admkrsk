<?php

namespace backend\controllers;

use common\models\CollectionRecord;
use common\models\GridSetting;
use common\models\CstExpert;
use common\models\CstProfile;
use common\models\CstResult;
use common\models\CstVote;
use common\models\Collection;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\models\search\CprofileSearch;
use backend\models\search\CexpertSearch;
use backend\models\forms\CstExpertForm;
use common\models\Form;

use common\models\CollectionColumn;

use yii\web\BadRequestHttpException;

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

    public function actionContest()
    {
        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            throw new BadRequestHttpException();

        //$activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);
        $activeContests = $contestCollection->getDataQuery()->getArray(true);

        $data = $links = [];
        
        //$profiles = CstProfile::find()->where(['state' => CstProfile::STATE_ACCEPTED])->all();
        $profiles = CstProfile::find()->all();

        foreach ($activeContests as $ckey => $cst) {

            $count = 0;
            $countTotal = 0;

            if(!empty($cst['participant_form']))
            {
                $form = Form::find()->where(['alias' => $cst['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        if(!isset($links[$ckey]))
                            $links[$ckey] = [];

                        $links[$ckey][] = $profile->id_profile;

                        $countTotal++;
                        if($profile->state == CstProfile::STATE_ACCEPTED)
                            $count++;
                    }
                }
            }

            $data[] = [
                'id' => $ckey,
                'name' => $cst['name'],
                'state' => $cst['contest_state'],
                'count' => $count,
                'countTotal' => $countTotal
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['id', 'name'],
            ],
        ]);
        
        $grid = GridSetting::findOne([
            'class' => static::gridContest,
            'user_id' => Yii::$app->user->id,
        ]);
        
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('contests', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }    

    public function actionEdit($id)
    {
        $contest = CollectionRecord::findOne($id);
        if(!$contest)
            throw new BadRequestHttpException();
        $model = $contest->getData(true);
        
        if(Yii::$app->request->get('flag'))
        {
            $experts = Yii::$app->request->get('experts');

            $sql = "DELETE FROM cst_contest_expert WHERE id_record_contest = {$id}";
            Yii::$app->db->createCommand($sql)->execute();

            foreach ($experts as $id_expert) {
                $sql = "INSERT INTO cst_contest_expert (id_record_contest, id_expert) VALUES ({$id}, {$id_expert})";
                Yii::$app->db->createCommand($sql)->execute();
            }

            return $this->redirect('/contest/contest');
        }

        /*
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
        */

        $expertsSql = "SELECT id_expert FROM cst_contest_expert WHERE id_record_contest = {$id}";
        $res = Yii::$app->db->createCommand($expertsSql)->queryAll();

        $experts = [];

        foreach($res as $item)
        {
            $experts[] = $item['id_expert'];
        }

        return $this->render('update', [
            'model' => $model,
            'experts' => $experts,
            'comment' => ''
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