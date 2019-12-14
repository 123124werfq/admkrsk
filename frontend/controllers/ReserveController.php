<?php

namespace frontend\controllers;


use Yii;
use common\models\Page;
use common\models\Collection;


class ReserveController extends \yii\web\Controller
{
    public function actionCandidateForm($page=nu)
    {
        $inputs = [];

        $page = Page::findOne(['alias'=>'candidate-form']);
        $collection = Collection::findOne(['alias'=>'reserv_anketa']);


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
