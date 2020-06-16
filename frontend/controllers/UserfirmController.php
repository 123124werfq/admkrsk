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
        $firm = $this->getFirm();

        if (!empty($firm))
            return $this->redirect('firm',['id'=>$firm->id_record]);

        $form = new UserFirmForm;

        return $this->render('index', [
            'page' => $page,
            'form' => $form,
        ]);
    }

    public function actionFirm($id)
    {
        $firm = $this->getFirm();
    }

    protected function getFirm()
    {
        $firm = FirmUser::find()->where(['id_user'=>Yii::$app->user->id])->one();
    }
}
