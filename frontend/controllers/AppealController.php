<?php

namespace frontend\controllers;

use common\models\AppealRequest;
use Yii;
use common\models\Page;


class AppealController extends \yii\web\Controller
{
    public function actionIndex($page = null)
    {
        $appeals = AppealRequest::find()->where(['id_user' => Yii::$app->user->id])->orderBy('id_request DESC')->all();

        return $this->render('index', [
            'page' => $page,
            'appeals' => $appeals

        ]);
    }

}
