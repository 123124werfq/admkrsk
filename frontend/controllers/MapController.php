<?php

namespace frontend\controllers;

use Yii;
use yii\web\NotFoundHttpException;

class MapController extends \yii\web\Controller
{
    public function actionIndex($page = null)
    {
        return $this->render('index', ['page' => $page]);
    }




}
