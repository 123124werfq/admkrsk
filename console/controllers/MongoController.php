<?php

namespace console\controllers;

use common\models\CollectionRecord;
use Yii;
use yii\console\Controller;

class MongoController extends Controller
{
    public function actionIndex()
    {
        $results = Yii::$app->db->createCommand("SELECT * FROM db_collection_value ORDER BY id_record")->queryAll();

        $records = CollectionRecord::find()->indexBy('id_record')->all();
        $id_record = null;

        $collections = [];
        foreach ($records as $key => $data)
            $collections[$data->id_collection] = $data->id_collection;

        foreach ($collections as $key => $id_collection)
        {
            Yii::$app->mongodb->createCommand()->delete('collection'.$id_collection,[]);
        }

        foreach ($results as $key => $data)
        {
            if (empty($id_record))
            {
                $insert = [];
                $id_record = $data['id_record'];
            }

            if ($id_record != $data['id_record'] || $key==(count($results)-1))
            {
                $collection = Yii::$app->mongodb->getCollection('collection'.$records[$id_record]->id_collection);
                $insert['id_record'] = $id_record;
                $collection->insert($insert);
                $insert = [];

                $id_record = $data['id_record'];
            }

            $insert[$data['id_column']] = $data['value'];
        }
    }
}


