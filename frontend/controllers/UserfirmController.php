<?php

namespace frontend\controllers;

use Yii;
use common\models\User;
use common\models\FirmUser;
use yii\filters\AccessControl;

class UserfirmController extends \yii\web\Controller
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
        $firm = FirmUser::find()->where(['id_user'=>Yii::$app->user->id])->one();

        if (!empty($firm))
            return $this->redirect('firm',['id'=>$firm->id_record]);

        if(strpos(Yii::$app->request->hostName, 'ants.'))
            return $this->redirect('/contests/select/select');

        return $this->render('index', ['page' => $page]);
    }


    public function actionFirm($id)
    {
        $firm = FirmUser::find()->where(['id_user'=>Yii::$app->user->id])->one();

       $smev->testMessage();
    }


}
