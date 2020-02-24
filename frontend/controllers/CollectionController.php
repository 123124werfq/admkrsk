<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use common\models\CollectionColumn;
use common\models\Page;
use common\models\Collection;
use common\models\SettingPluginCollection;
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

        $data = $model->getData(true);
        $data['id_record'] = $model->id_record;
        $data['created_at'] = $model->created_at;
        $data['updated_at'] = $model->updated_at;

		return $this->render('view', [
			'data' => $data,
			'columns'=> $model->collection->getColumns()->indexBy('alias')->all(),
			'template'=>$model->collection->template,
			'page'=>$page,
		]);
	}

    public function actionCoords($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $collection = $this->findModel($id);

        if (empty($collection->id_column_map))
            return [];
        /*$columns = $collection->getColumns()->indexBy('id_column')->all();

        if (empty($columns[$id_column]) || $columns[$id_column]->type!=CollectionColumn::TYPE_MAP)
            return [];*/

        $records = $collection->getData();

        $columns = $collection->getColumns()->indexBy('id_column')->all();

        $points = [];

        foreach ($records as $key => $data)
        {
            if (!empty($data[$collection->id_column_map][0]) && is_array($data[$collection->id_column_map]))
            {

                $content = '';

                foreach ($collection->label as $key => $id_column) {
                    if (!empty($data[$id_column]) && !empty($columns[$id_column]))
                        $content .= '<tr><th>'.$columns[$id_column]->name.'</th><td>'.$data[$id_column].'</td></tr>';
                }

                $points[] = [
                    'x' => str_replace(',', '.', $data[$collection->id_column_map][0]),
                    'y' => str_replace(',', '.', $data[$collection->id_column_map][1]),
                    'icon' => '',
                    'content' => '<table>'.$content.'</table>'
                ];
            }
        }

        return $points;
    }

    public function actionDownload($key,$id_page)
    {
        $settings = SettingPluginCollection::find()->where(['key'=>$key,'id_page'=>$id_page])->one();

        if (empty($settings))
            throw new NotFoundHttpException('The requested page does not exist.');

        $options = json_decode($settings->settings,true);

        $head = [];

        foreach ($settings->columns as $key => $column)
            $head[] = $column->name;

        $query = $settings->collection->getDataQueryByOptions($options);
        $allrows = $query->getArray();

        $allrows = array_merge([$head],$allrows);

        header("Content-Disposition: attachment; filename=\"{$settings->collection->name}.xls\"");
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');

        fputs($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        foreach ($allrows as $data)
        {
            fputcsv($out, $data,"\t");
        }
        fclose($out);
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
        if (($model = Collection::findOneWithDeleted((int)$id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
