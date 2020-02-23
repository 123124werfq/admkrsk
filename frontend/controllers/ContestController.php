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


class ContestController extends \yii\web\Controller
{

    public function actionParticipantForm($page=null)
    {
        $inputs = [];

        $page = Page::findOne(['alias'=>'candidate-form']);
        $collection = Collection::findOne(['alias'=>'contest_1']); // предусмотреть выбор конкурса

        if(!$collection || !$page)
            throw new BadRequestHttpException();

        $profile = ContestProfile::findOne(['id_user' => Yii::$app->user->id]);

        $model = new FormDynamic($collection->form);

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

                if (Yii::$app->request->isAjax)
                    return $collection->form->message_success?$collection->form->message_success:'Спасибо, данные отправлены';

                if (!empty($collection->form->url))
                    return $this->redirect($collection->form->url);

                if (!empty($collection->form->id_page) && $url = Page::getUrlByID($collection->form->id_page))
                    return $this->redirect($url);
            }
            else
                echo "Данные не сохранены";
        }

        return $this->render('form', [
            'form'      => $collection->form,
            'page'      => $page,
            'inputs'    => $inputs,
            'record'    => !empty($profile->record)?$profile->record:null
        ]);
    }

    public function actionIndex($page=null)
    {
        //$page = Page::findOne(['alias'=>'reserve']);
        //var_dump($_GET['id']);
        //echo "Выбор конкурса и анкеты";
//die();
        return $this->render('//site/page', ['page'=>$page]);
    }

    public function actionSelect($page=null)
    {
        $profiles = [];

        return $this->render('select', [
            'profiles' => $profiles,
            'page' => $page
        ]);
    }

}