<?php

namespace frontend\controllers;

use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Form;
use common\models\ContestProfile;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;


use common\models\CstProfile;


class ContestController extends \yii\web\Controller
{

    public function actionParticipantForm($page=null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome(); // логин?
        }
        $user = Yii::$app->user->identity;
        $inputs = [];

        $formAlias = Yii::$app->request->get('contest');
        $idprofile = Yii::$app->request->get('ida');
        $form = Form::find()->where(['alias' => $formAlias])->one();

        if(!$form)
            throw new BadRequestHttpException();
        
        $collection = $form->collection;

        $page = Page::findOne(['alias'=>'select']);

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $profile = CstProfile::find()->where(['id_user' => Yii::$app->user->id, 'id_profile' => $idprofile])->one();

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            $record = false;

            if($profile && $profile->id_record_anketa)
                $record = $collection->updateRecord($profile->id_record_anketa, $prepare);

            if(!$record)
                $record = $collection->insertRecord($prepare);

            if ($record) {
                if(!$profile)
                    $profile = new CstProfile;

                $profile->id_user = Yii::$app->user->id;
                $profile->id_record_anketa = $record->id_record;
                $profile->id_record_contest = $collection->id_collection;
                $profile->save();

                if (Yii::$app->request->isAjax)
                    return $form->message_success?$collection->form->message_success:'Спасибо, данные отправлены';

                if (!empty($form->url))
                    return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                    return $this->redirect($url);
            }
            else
                echo "Данные не сохранены";
        }

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            return false;

        $contests = $contestCollection->getDataQuery()->getArray(true);

        foreach ($contests as $ackey => $contest) {
            if(!empty($contest['participant_form']))
            {
                if($form->alias == $contest['participant_form'])
                    $contestname = $contest['name'];
            }
        }

        return $this->render('form', [
            'form'      => $form,
            'page'      => $page,
            'inputs'    => $inputs,
            'contestname' => $contestname,
            'record'    => !empty($profile->record)?$profile->record:null
        ]);
    }

    public function actionIndex($page=null)
    {
        return $this->redirect('/contests/select/select');
        //return $this->render('//site/page', ['page'=>$page]);
    }

    public function actionSelect($page=null)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome(); // логин?
        }

        $user = Yii::$app->user->identity;

        $profiles = CstProfile::find()->where(['id_user' => $user->id])->all();

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection || !$page)
            throw new BadRequestHttpException();

        $activeContests = $contestCollection->getDataQuery()->whereByAlias(['<>', 'contest_state', 'Конкурс завершен'])->getArray(true);

        $links = [];
        foreach ($activeContests as $ackey => $contest) {
            if(!empty($contest['participant_form']))
            {
                $form = Form::find()->where(['alias' => $contest['participant_form']])->one();
                if(!$form)
                    continue;

                foreach ($profiles as $profile) {
                    if($form->id_collection == $profile->id_record_contest)
                    {
                        if(!isset($links[$ackey]))
                            $links[$ackey] = [];

                        $links[$ackey][] = $profile->id_profile;
                    }
                }
            }
        }

        return $this->render('select', [
            'profiles' => $profiles,
            'contests' => $activeContests,
            'links' => $links,
            'page' => $page
        ]);
    }

}