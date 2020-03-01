<?php

namespace console\controllers;

use common\models\Collection;
use common\models\CollectionRecord;
use common\models\Service;

use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;
use common\models\CollectionColumn;

use common\helpers\ProgressHelper;

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

    protected function actionUpdateCustomColumn($id_column)
    {
        $column = CollectionColumn::findOne($id_column);
        $collection = $column->collection;

        $mongoCollection = Yii::$app->mongodb->getCollection('collection'.$collection->id_collection);

        $records = $collection->getData([],true);

        $total = count($records);
        ProgressHelper::startProgress(0, $total, "Обновление значенией ");

        $count = 0;

        foreach ($records as $id_record => $data)
        {
            $modelRecord = CollectionRecord::findOne($id_record);
            $dataString = $modelRecord->getDataAsString(true,true);

            $dataMongo = ['col'.$column->id_column => CollectionColumn::renderCustomValue($column->template,$dataString)];
            $mongoCollection->update(['id_record' => $id_record], $dataMongo);

            ProgressHelper::updateProgress($count, $total);

            $count++;
        }

        ProgressHelper::endProgress("100% Done." . PHP_EOL);
    }


    public function actionAddressFix()
    {
        set_time_limit(0);
        $sql = "SELECT * FROM form_form WHERE is_template = 2 AND name LIKE '%Адрес%'";
        $forms = Form::find()->where("is_template = 2 AND name LIKE '%Адрес%'")->all();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($forms as $key => $form)
            {
                $prefix = '';
                $formModel = null;

                foreach ($form->rows as $rkey => $row)
                {
                    foreach ($row->elements as $ekey => $element)
                    {
                        if (!empty($element->input))
                        {
                            $prefix = $element->input->column->alias;

                            $formModel = $element->input->form;
                            $element->input->column->delete();
                            $element->input->delete();
                        }
                    }
                }

                $element = FormElement::find()->where(['id_form'=>$form->id_form])->one();

                if (!empty($element) && !empty($formModel))
                {
                    $element->id_form = null;

                    $prefix = explode('_', $prefix);
                    if (count($prefix)>1)
                    {
                        array_pop($prefix);
                        $prefix = implode('_', $prefix).'_address';
                    }
                    else
                        $prefix = 'address';

                    $input = new FormInput;
                    $input->id_form = $formModel->id_form;
                    $input->fieldname = $prefix;
                    $input->options = json_decode('{"show_city": "1", "show_room": "1", "show_house": "1", "show_region": "1", "show_street": "1", "show_country": "1", "show_district": "1", "show_postcode": "1", "show_subregion": "1"}',true);
                    $input->name = 'Адрес';
                    $input->type = CollectionColumn::TYPE_ADDRESS;

                    $count = Yii::$app->db->createCommand("SELECT count(*) FROM form_input WHERE fieldname = '$input->fieldname' AND id_form = $input->id_form")->queryScalar();

                    if ($count>0)
                        $input->fieldname = 'full_'.$input->fieldname;

                    if ($input->save())
                    {
                        $column = new CollectionColumn;
                        $column->name = $input->name;
                        $column->alias = $input->fieldname;
                        $column->id_collection = $formModel->id_collection;
                        $column->type = $input->type;

                        if ($column->save())
                        {
                            $input->id_column = $column->id_column;
                            $input->updateAttributes(['id_column']);

                            $element->id_input = $input->id_input;
                            $element->save();
                        }
                    }
                    else
                    {
                        var_dump($input->id_form);
                        var_dump($input->fieldname);

                        print_r($input->errors);
                        $transaction->rollBack();
                        die();
                    }
                }
            }

            $transaction->commit();
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();

            throw $e;
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