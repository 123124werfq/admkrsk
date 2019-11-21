<?php

namespace frontend\controllers;

use common\models\Form;

class FormController extends \yii\web\Controller
{
    public function actionView($id)
    {
    	$model = Form::findOne($id);

        return $this->render('index',['model'=>$model]);
    }
}
