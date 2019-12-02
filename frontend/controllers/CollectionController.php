<?php

namespace frontend\controllers;

use common\models\CollectionRecord;

class CollectionController extends \yii\web\Controller
{
    public function actionView($id)
    {
    	$model = CollectionRecord::findOne($id);

    	if (empty($model))
    		throw new NotFoundHttpException('The requested page does not exist.');


        return $this->render('view', ['data' => $model->getData(true),'template'=>$model->collection->template]);
    }
}
