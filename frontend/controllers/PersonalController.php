<?php

namespace frontend\controllers;

use Yii;
use common\models\Smev;
use common\models\User;
use yii\filters\AccessControl;

class PersonalController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex($page = null)
    {
        if(strpos(Yii::$app->request->hostName, 'ants.'))
            return $this->redirect('/contests/select/select');

        return $this->render('index', ['page' => $page]);
    }


    public function actionStest()
    {
       $smev = new Smev;

       $smev->testMessageEpgu();

    }

    public function actionUserProfile($page = null)
    {
        $user = User::findOne(Yii::$app->user->id);

        return $this->render('profile', ['page' => $page, 'user' => $user]);
    }
}
