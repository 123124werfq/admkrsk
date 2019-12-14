<?php

namespace frontend\controllers;


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

                if(!$profile) {
                    $profile = new HrProfile;
                    $profile->created_at = time();
                }
                else
                    $profile->updated_at = time();

                $profile->id_user = Yii::$app->user->id;
                $profile->id_record = $record->id_record;
                $profile->save();

                // тут привзяка к должностям
            }

        }

        return $this->render('form', [
            'form'=>$collection->form,
            'page'=>$page,
            'inputs'=>$inputs,
        ]);
    }

    public function actionIndex($page=null)
    {
        $page = Page::findOne(['alias'=>'reserve']);

        return $this->render('//site/page', ['page'=>$page]);
    }

}
