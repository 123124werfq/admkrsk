<?php

namespace frontend\controllers;

use Yii;

use common\models\Form;
use common\models\FormDynamic;
use common\models\Page;
use common\models\CollectionRecord;
use common\models\ServiceComplaintForm;
use common\models\FormInput;

use yii\widgets\ActiveForm;
use yii\web\Response;

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
                if (Yii::$app->request->isAjax)
                {
                    Yii::$app->response->format = Response::FORMAT_JSON;

                    return [
                        'success'=>$form->message_success?$form->message_success:'Спасибо, данные отправлены'
                    ];
                }

                if (!empty($form->url))
                	return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                	return $this->redirect($url);
            }
            else
            {
                Yii::$app->response->format = Response::FORMAT_JSON;

                return [
                    'error'=>"Ошибка записи, данные не сохранены, пожалуйста повторите попытку позднее",
                ];
            }
        }
        else
        {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
    }

    public function actionFormCollection($id,$arrayGroup='')
    {
        $input = FormInput::findOne($id);

        if (empty($input))
            return '';

        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset'=>false,
            'yii\web\YiiAsset'=>false,
        ];

        $activeForm = \yii\widgets\ActiveForm::begin([
            'fieldConfig' => [
                'template' => '{input}{error}',
            ]
        ]);

        return $this->renderAjax('collection_form',[
            'form'=>$activeForm,
            'arrayGroup'=>$arrayGroup,
            'input'=>$input,
        ]);
    }

    public function actionGetCategories($id)
    {
        $firms = ServiceComplaintForm::find()
            ->groupBy('id_record_category')
            ->select('id_record_category')
            ->asArray()
            ->indexBy('id_record_category')
            ->all();

        $ids = array_keys($firms);

        $records = CollectionRecord::find()->where(['id_record'=>$ids])->all();

        $firms = [];

        $output = '';

        foreach ($records as $key => $record)
            $output .= '<option value="'.$record->id_record.'">'.$record->lineValue.'</option>';

        return $output;
    }

    protected function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Такой страницы не существует');
    }
}
