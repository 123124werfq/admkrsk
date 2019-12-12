<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use common\models\Page;

class CollectionController extends \yii\web\Controller
{
	public function actionView($id,$id_page)
	{
		$page  = Page::findOne($id_page);
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

	public function actionRecordList($id,$q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $collection = $this->findModel($id);
        $collection = $stripos->getArray();

        $i = 0;
        $results = [];

        foreach ($collection as $key => $value)
        {
            if ($i>15)
                break;

            if (stripos($value, $q))
            {
                $results[] = [
                    'id' => $key,
                    'text' => $value,
                ];

                $i++;
            }
        }

        return ['results' => $results];
    }

    protected function findModel($id)
    {
        if (($model = Collection::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
