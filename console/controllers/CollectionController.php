<?php

namespace console\controllers;

use common\models\Collection;
use common\models\CollectionRecord;
use common\models\Service;

use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;
use Yii;
use yii\console\Controller;

class CollectionController extends Controller
{
    public function actionIndex()
    {
        $collection = Collection::find()->where('id_form IS NULL')->all();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($collection as $key => $collection)
            {
                $collection->createForm();
            }

            $transaction->commit();

        } catch (\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionAlias()
    {
        $inputs = FormInput::find()->where("fieldname IS NULL OR fieldname = ''")->all();

        foreach ($inputs as $key => $input)
        {
            $input->fieldname = 'input'.$input->id_input;
            $input->updateAttributes(['fieldname']);

            if (!empty($input->column))
            {
                $input->column->alias = $input->fieldname;
                $input->column->updateAttributes(['alias']);
            }
        }
    }

    public function actionService()
    {
        $collection = Collection::find()->where(['alias'=>'service_offices'])->one();

        $data = $collection->getData([],true);

        foreach ($data as $key => $row)
        {
            if (!empty((int)$row['UslugaID']))
            {
                $record = CollectionRecord::findOne($key);
                $service = Service::findOne((int)$row['UslugaID']);

                if (!empty($record) && !empty($service))
                    $service->link('firms',$record);
            }
        }
    }

    public function actionMongofix()
    {
        set_time_limit(0);

        $collection = Collection::find()->orderBy('id_collection DESC')->all();

        foreach ($collection as $ckey => $collection)
        {
            $query = new \yii\mongodb\Query;
            $query->from('collection'.$collection->id_collection);

            $mongocollection = Yii::$app->mongodb->getCollection('collection'.$collection->id_collection);

            foreach ($query->all() as $rkey => $row)
            {
                $updateDate = ['id_record'=>$row['id_record']];

                foreach ($row as $id_column => $value)
                {
                    if (is_numeric($id_column))
                        $updateDate['col'.$id_column] = $value;
                }

                $mongocollection->update(['id_record'=>$row['id_record']],$updateDate);
            }
        }
    }
}