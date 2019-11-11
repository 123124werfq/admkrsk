<?php

namespace frontend\controllers;

class PravoController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $this->layout = 'pravo';
        return $this->render('index');
    }

}
