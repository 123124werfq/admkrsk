<?php

namespace frontend\controllers;


use common\models\HrContest;
use common\models\HrExpert;
use common\models\HrVote;
use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\HrProfile;
use yii\web\BadRequestHttpException;


class ReserveController extends \yii\web\Controller
{
    public function actionCandidateForm($page=null)
    {
        $inputs = [];

        $page = Page::findOne(['alias'=>'candidate-form']);
        $collection = Collection::findOne(['alias'=>'reserv_anketa']);

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $profile = HrProfile::findOne(['id_user' => Yii::$app->user->id]);

        if(!$profile || true)
            $model = new FormDynamic($collection->form);
        else
            $model = Form::findOne($collection->form->id_form);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $prepare = $model->prepareData(true);

            if ($record = $collection->insertRecord($prepare)) {

                if(!$profile)
                    $profile = new HrProfile;

                $profile->id_user = Yii::$app->user->id;
                $profile->id_record = $record->id_record;
                $profile->save();

                return $this->render('result', ['page'=>$page, 'form'=>$collection->form]);
            }

        }

        return $this->render('form', [
            'form'      => $collection->form,
            'page'      => $page,
            'inputs'    => $inputs,
            'record'    => $profile->record
        ]);
    }

    public function actionIndex($page=null)
    {
        $page = Page::findOne(['alias'=>'reserve']);

        return $this->render('//site/page', ['page'=>$page]);
    }

    public function actionVote()
    {
        $now = time();
        //$contest = HrContest::find()->where("\"begin\"<$now AND \"end\">$now")->one();
        $contest = HrContest::find()->one();

        if(!$contest)
            throw new BadRequestHttpException();

        $expert = HrExpert::findOne(['id_user' => Yii::$app->user->id]);

        if(!$expert)
            throw new BadRequestHttpException();


        $enabled = false;

        foreach ($contest->experts as $cexpert)
            if($cexpert->id_expert == $expert->id_expert)
                $enabled = true;

        if(!$enabled)
            throw new BadRequestHttpException();

        $votes = HrVote::find()->where(['id_expert' => $expert->id_expert, 'id_contest' => $contest->id_contest])->all();

        return $this->render('vote', [
            'data' => $contest,
            'expert' => $expert,
            'votes' => $votes
        ]);
    }


    public function actionProfile($id)
    {

    }



}
