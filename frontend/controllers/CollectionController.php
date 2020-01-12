<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use common\models\CollectionColumn;
use common\models\Page;
use common\models\Collection;
use yii\web\Response;
use Yii;

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

    public function actionCoords($id,$id_column)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $collection = $this->findModel($id);
        $columns = $collection->getColumns()->indexBy('id_column')->all();

        if (empty($columns[$id_column]) || $columns[$id_column]->type!=CollectionColumn::TYPE_MAP)
            return [];

        $records = $collection->getData();

        $points = [];
        foreach ($records as $key => $data)
        {
            if (!empty($data[$id_column]) && is_array($data[$id_column]))
            {
                $points[] = [
                    'x' => $data[$id_column][0],
                    'y' => $data[$id_column][1],
                    'icon' => '',
                    'content' => 'Пробная метка'
                ];
            }
        }

        return $points;
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

    public function actionWord($id_record)
    {
        $record = \common\models\CollectionRecord::findOne($id_record);

        if (empty($record))
            throw new NotFoundHttpException('The requested page does not exist.');

        $export_path = $record->collection->form->makeDoc($record);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="word_template.docx"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_path));

        readfile($export_path);
    }

    protected function findModel($id)
    {
        if (($model = Collection::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
