<?php

namespace frontend\controllers;

use common\models\ServiceComplaintForm;
use common\models\CollectionRecord;

class ComplaintController extends \yii\web\Controller
{
    public function actionIndex($page=null)
    {
    	echo "string";

        $firms = ServiceComplaintForm::find()
            ->groupBy('id_record_firm')
            ->select('id_record_firm')
            ->asArray()
            ->indexBy('id_record_firm')
            ->all();

        $ids = array_keys($firms);

        $records = CollectionRecord::find()->where(['id_record'=>$ids])->all();

        $firms = [];

        foreach ($records as $key => $record)
            $firms[$record->id_record] = $record->getLineValue();
        /*$collectionFirm = Colletion::find()->where(['alias'=>'appeal_firms'])->one();

        if (empty($collectionFirm))
            throw new NotFoundHttpException('Не заполнен справочник организаций');*/

        return $this->render('complaint',[
            'firms'=>$firms,
            'page'=>$page,
        ]);
    }

    public function actionCreate($page,$id_form, $id_category)
    {
    	$form = ServiceComplaintForm::find()
    				->with(['form'])
    				->where(['id_record_form'=>$id_form,'id_category'=>$id_category])
    				->one();

    	if (empty($form))
    		throw new NotFoundHttpException('Такой страницы не существует');

    	$form = $form->$form

    	$collection = $form->collection;

        $model = new FormDynamic($form);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $prepare = $model->prepareData(true);

            if ($collection->insertRecord($prepare))
            {
                echo "OK!";

                if (!empty($form->url))
                	return $this->redirect($form->url);

                if (!empty($form->id_page) && $url = Page::getUrlByID($form->id_page))
                	return $this->redirect($url);

                return $this->redirect($form->message_success);
            }
            else
                echo "Данные не сохранены";
        }

    	$this->render('create',[
    		'form'=>$form,
    		'page'=>$page,
    	]);
    }
}