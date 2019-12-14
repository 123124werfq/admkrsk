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

                /*$form = new Form;
                $form->id_collection = $collection->id_collection;
                $form->name = $collection->name;

                if ($form->save())
                {
                    foreach ($collection->columns as $ckey => $column)
                    {
                        $input = new FormInput;
                        $input->label = $input->name = $column->name;
                        $input->type = $column->type;
                        $input->id_form = $form->id_form;
                        $input->id_column = $column->id_column;

                        if (!$input->save())
                            print_r($input->errors);

                        $row = new FormRow;
                        $row->id_form = $form->id_form;
                        if (!$row->save())
                            print_r($row->errors);

                        $element = new FormElement;
                        $element->id_row = $row->id_row;
                        $element->id_input = $input->id_input;
                        if (!$element->save())
                            print_r($element->errors);
                    }

                    $collection->id_form = $form->id_form;
                    $collection->updateAttributes(['id_form']);
                }
                else
                    print_r($form->errors);*/
            }

            $transaction->commit();

        } catch (\Exception $e)
        {
            $transaction->rollBack();
            throw $e;
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