<?php

namespace frontend\controllers;

use Yii;

use common\models\Form;
use common\models\FormDynamic;
use common\models\Page;
use common\models\FormInput;

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

    public function actionFormCollection($id)
    {
        $input = FormInput::findOne($id);

        if (empty($input))
            return '';

        $activeForm = \yii\widgets\ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{input}{error}',
            ]
        ]);

        return $this->renderAjax('collection_form',[
            'form'=>$activeForm,
            'input'=>$input,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }
}
