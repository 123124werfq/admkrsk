<?php

namespace frontend\controllers;


use Yii;
use common\models\Page;
use common\models\Collection;
use common\models\FormDynamic;
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

        $model = new FormDynamic($collection->form);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $prepare = $model->prepareData(true);

            if ($record = $collection->insertRecord($prepare)) {
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
