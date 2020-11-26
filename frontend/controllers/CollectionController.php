<?php

namespace frontend\controllers;

use common\models\CollectionRecord;
use common\models\CollectionColumn;
use common\models\Page;
use common\models\Collection;
use common\models\SettingPlugin;
use yii\web\Response;
use Yii;
use yii\web\NotFoundHttpException;

class CollectionController extends \yii\web\Controller
{
	public function actionView($id,$id_page,$id_collection=null)
	{
		$page  = Page::findOne($id_page);
		$model = CollectionRecord::findOne($id);

		if (empty($model) || empty($page))
			throw new NotFoundHttpException('The requested page does not exist.');

        $data = $model->getDataRaw(true,true);

        $data['id_record'] = $model->id_record;
        $data['created_at'] = $model->created_at;
        $data['updated_at'] = $model->updated_at;

        if (empty($id_collection))
            $collection = $model->collection;
        else
            $collection = Collection::findOne($id_collection);

        if (empty($collection))
            throw new NotFoundHttpException('The requested page does not exist.');

		return $this->render('view', [
			'data' => $data,
			'columns'=> $collection->getColumns()->indexBy('alias')->all(),
			'template'=> $collection->template,
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
            if (!empty($data[$collection->id_column_map][0]) || !empty($data[$collection->id_column_map]['lat'])) /*&& is_array($data[$collection->id_column_map])*/
            {
                $content = '';
                $title = '';

                foreach ($collection->label as $key => $id_column) {
                    if (!empty($data[$id_column]) && !empty($columns[$id_column]))
                    {
                        $content .= '<tr><th>'.$columns[$id_column]->name.'</th><td>'.$data[$id_column].'</td></tr>';
                        if(in_array(mb_strtolower($columns[$id_column]->name, 'UTF8'), ['название', 'наименование'])) $title = $data[$id_column];
                    }
                }

                // защита от перепутанных координат

                if ($columns[$collection->id_column_map]->type == CollectionColumn::TYPE_ADDRESS)
                {
                    $x = (float)str_replace(',', '.', $data[$collection->id_column_map]['lat']??'');
                    $y = (float)str_replace(',', '.', $data[$collection->id_column_map]['lon']??'');
                }
                else
                {
                    $x = (float)str_replace(',', '.', $data[$collection->id_column_map][0]);
                    $y = (float)str_replace(',', '.', $data[$collection->id_column_map][1]);
                }

                if ($x>$y)
                    [$x, $y] = [$y, $x];

                $points[] = [
                    'x' => $x,
                    'y' => $y,
                    'icon' => '',
                    'content' => '<table>'.$content.'</table>',
                    'title' => $title
                ];
            }
        }

        return $points;
    }

    public function actionDownload($key,$id_page)
    {
        $settings = SettingPlugin::find()->where(['key'=>$key,'id_page'=>$id_page])->one();

        if (empty($settings))
            throw new NotFoundHttpException('The requested page does not exist.');

        $options = json_decode($settings->settings,true);

        if (!empty($options['download_columns']))
        {
            $options['columns'] = [];
            foreach ($options['download_columns'] as $key => $id_column) {
                   $options['columns'][] = ['id_column'=>$id_column];
            }

            $settings->settings = json_encode($options);
        }

        $head = [];
        $columns = [];

        foreach ($settings->columns as $key => $column)
        {
            $columns[$column->id_column] = $column;
            $head[] = $column->name;
        }

        $query = $settings->collection->getDataQueryByOptions($options);
        $allrows = $query->getArray();

        $allrows = array_merge([$head],$allrows);

        header("Content-Disposition: attachment; filename=\"".iconv('UTF-8', 'CP1251', $settings->collection->name).".csv\"");
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Pragma: no-cache");
        header("Expires: 0");
        $out = fopen("php://output", 'w');

        fputs($out, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
        foreach ($allrows as $data)
        {
            foreach ($data as $dkey => $value)
            {
                if (is_array($value))
                    $data[$dkey] = implode(', ', $value);

                $data[$dkey] = htmlspecialchars_decode(strip_tags($data[$dkey]));
            }

            fputcsv($out, $data,';');
        }

        fclose($out);
    }

	public function actionRecordList($id,$q='',$id_column=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $collection = $this->findModel($id);

        if (empty($collection))
            throw new NotFoundHttpException('The requested page does not exist.');

        $collection = $collection->getArray($id_column);

        $i = 0;
        $results = [];

        foreach ($collection as $key => $value)
        {
            if ($i>15)
                break;

            if ($q=='' || stripos($value, $q))
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
        header('Content-Disposition: attachment; filename="'.$id_record.'.docx"');
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
