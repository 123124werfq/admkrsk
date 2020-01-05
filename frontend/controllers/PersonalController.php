<?php

namespace frontend\controllers;

use common\models\Smev;

class PersonalController extends \yii\web\Controller
{
    public function actionIndex($page = null)
    {

        return $this->render('index', ['page' => $page]);
    }


    public function actionStest()
    {
       $smev = new Smev;

       $smev->testMessage();

    }

    public function actionUserProfile()
    {

    }

}
