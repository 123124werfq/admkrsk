<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use common\models\Page;

class CollectionController extends \yii\web\Controller
{
	public function actionView($id,$id_page)
	{
		$page  = page::findOne($id_page);
		$model = CollectionRecord::findOne($id);

		if (empty($model) || empty($page))
			throw new NotFoundHttpException('The requested page does not exist.');

		return $this->render('view', [
			'data' => $model->getData(true),
			'columns'=> $model->collection->getColumns()->indexBy('alias')->all(),
			'template'=>$model->collection->template,
			'page'=>$page,
		]);
	}
}
