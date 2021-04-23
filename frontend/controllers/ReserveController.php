<?php

namespace frontend\controllers;


use common\models\HrContest;
use common\models\HrExpert;
use common\models\HrProfilePositions;
use common\models\HrVote;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
//use frontend\modules\api\models\CollectionRecord;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\HrProfile;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

use yii\widgets\ActiveForm;
use yii\web\Response;

class ReserveController extends \yii\web\Controller
{

    public function actionCandidateForm($page=null)
    {
        if(Yii::$app->user->isGuest)
            return $this->redirect('/login');

        $inputs = [];

        $page = Page::findOne(['alias'=>'candidate-form']);
        $collection = Collection::findOne(['alias'=>'reserv_anketa']);

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $profile = HrProfile::findOne(['id_user' => Yii::$app->user->id]);

        $model = new FormDynamic($collection->form);

        /*
        if(!$profile || true)
            $model = new FormDynamic($collection->form);
        else
            $model = Form::findOne($collection->form->id_form);
        */

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            $record = false;

            if($profile && $profile->id_record)
                $record = $collection->updateRecord($profile->id_record, $prepare);

            if(!$record)
                $record = $collection->insertRecord($prepare);

            if ($record) {
                if(!$profile)
                    $profile = new HrProfile;

                $profile->id_user = Yii::$app->user->id;
                $profile->id_record = $record->id_record;
                $profile->save();

                //var_dump($profile->getErrors());

                if (Yii::$app->request->isAjax)
                {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success'=>$collection->form->message_success?$collection->form->message_success:'Спасибо, данные отправлены'
                    ];
                }

                if (!empty($collection->form->url))
                    return $this->redirect($collection->form->url);

                if (!empty($collection->form->id_page) && $url = Page::getUrlByID($collection->form->id_page))
                    return $this->redirect($url);
            }
            else
                echo "Данные не сохранены";
        }
        elseif (Yii::$app->request->isAjax)
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        // определяем, по каким должностям тип уже в резерве
        $rSQL = "select distinct id_record_position as id from hr_reserve where deleted_at is null and id_profile = ".$profile->id_profile;
        //$rSQL = "select distinct id_record_position as id from hr_reserve";
        $rCount = Yii::$app->db->createCommand($rSQL)->queryAll();
        $excludeChecks = [];
        foreach ($rCount as $key => $value) {
            $excludeChecks[] = $value['id'];
        }

        return $this->render('form', [
            'form'      => $collection->form,
            'page'      => $page,
            'inputs'    => $inputs,
            'record'    => !empty($profile->record)?$profile->record:null,
            'exclchks'  =>  $excludeChecks
        ]);
    }

    public function actionIndex($page=null)
    {
        $page = Page::findOne(['alias'=>'reserve']);

        return $this->render('//site/page', ['page'=>$page]);
    }

    public function actionVote($id=null)
    {
        $contest = HrContest::active();

        if(!$contest)
            throw new BadRequestHttpException();

        $expert = HrExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();


        $enabled = false;

        foreach ($contest->experts as $cexpert)
            if($cexpert->id_expert == $expert->id_expert)
                $enabled = true;

        if(!$enabled)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();

        $votes = HrVote::find()->where(['id_expert' => $expert->id_expert, 'id_contest' => $contest->id_contest])->all();

        return $this->render('vote', [
            'data' => $contest,
            'expert' => $expert,
            'votes' => $votes
        ]);
    }


    public function actionProfile($id)
    {
        $contest = HrContest::active();

        if(!$contest)
            throw new BadRequestHttpException();

        $expert = HrExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();

        $enabled = false;

        foreach ($contest->experts as $cexpert)
            if($cexpert->id_expert == $expert->id_expert)
                $enabled = true;

        if(!$enabled)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();

        $profile = HrProfile::findOne($id);
        if(!$profile)
            throw new BadRequestHttpException();

        $enabled = false;

        foreach ($contest->profiles as $cprofile)
            if($cprofile->id_profile == $profile->id_profile)
                $enabled = true;

        if(!$enabled)
            return $this->render('expertsonly');
            //throw new BadRequestHttpException();

        if (!empty(Yii::$app->request->post()))
        {
            //var_dump(Yii::$app->request->post());

            $positions =  Yii::$app->request->post('position');

            foreach ($positions as $idp => $position)
            {
                $vote = HrVote::find()->where(['id_expert' => $expert->id_expert, 'id_contest' => $contest->id_contest, 'id_record' => $idp])->one();

                if(!$vote)
                {
                    $vote = new HrVote;
                    $vote->id_expert = $expert->id_expert;
                    $vote->id_contest = $contest->id_contest;
                    $vote->id_profile = $profile->id_profile;
                    $vote->id_record = $idp;
                }

                $vote->value = $position;
                if(!$vote->save())
                {
                    var_dump($vote->getErrors()); die();
                }
            }

            return $this->redirect('/reserve/vote');
        }


        $collectionRecord = CollectionRecord::findOne($profile->id_record);

        //$insertedData = $collectionRecord->getData();

        $columns = CollectionColumn::find()->where(['id_collection' => $collectionRecord->id_collection])->indexBy('id_column')->orderBy('ord')->all();

        /*foreach ($insertedData as $rkey => $ritem)
        {
            $formFields[$columns[$rkey]->alias] = ['value' => empty($ritem)?"[не заполнено]":$ritem, 'name' => $columns[$rkey]->name, 'ord' => $columns[$rkey]->ord];
        }

        usort($formFields, function($a, $b){
            return ($a['ord']<$b["ord"])?-1:1;
        });*/

        $votes = HrVote::find()->where(['id_expert' => $expert->id_expert, 'id_contest' => $contest->id_contest, 'id_profile' => $profile->id_profile])->all();

        $outvotes = [];
        foreach ($votes as $vote)
            $outvotes[$vote->id_record] = $vote->value;

        return $this->render('profile', [
            'data' => $contest,
            'expert' => $expert,
            'profile' => $profile,
            //'insertedData' => $insertedData,
            'collectionRecord'=>$collectionRecord,
            'columns'=>$columns,
            'outvotes' => $outvotes
        ]);
    }


    public function actionFinal($id)
    {
        $contest = HrContest::findOne($id);

        if(!$contest)
            throw new BadRequestHttpException();

        $expert = HrExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();

        if($contest->id_user != $expert->id_expert)
            return $this->render('expertsonly');
//            throw new BadRequestHttpException();


        $votes = HrVote::find()->where(['id_contest' => $contest->id_contest])->all();

        return $this->render('overall', [
            'data' => $contest,
            'votes' => $votes
        ]);
    }
}