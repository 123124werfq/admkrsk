<?php

namespace frontend\controllers;

use Yii;

use common\models\Form;
use common\models\FormDynamic;
use common\models\Page;

class FormController extends \yii\web\Controller
{
    public function actionView($id)
    {
    	$model = Form::findOne($id);

        return $this->render('index',['model'=>$model]);
    }

    public function actionCreate($id)
    {
    	$form = $this->findModel($id);
    	$collection = $form->collection;

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            if ($collection->insertRecord($prepare))
            {
                if (!empty($form->url))
                	return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                	return $this->redirect($url);

                return $this->redirect($form->message_success);
            }
            else
                echo "Данные не сохранены";
        }
    }

    protected function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }
}
