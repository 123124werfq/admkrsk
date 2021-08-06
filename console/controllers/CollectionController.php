<?php

namespace console\controllers;

use common\models\Collection;
use common\models\CollectionRecord;
use common\models\Service;

use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;
use common\models\FormVisibleInput;
use common\models\CollectionColumn;
use common\modules\log\models\RecordLog;


use common\helpers\ProgressHelper;

use yii\mongodb\Query;

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

    public function actionDeleteForms()
    {
        $collection = Collection::findDeleted();

        foreach ($collection as $key => $collection)
        {
            foreach ($collection->forms as $key => $form)
                if ($form->delete())
                        $form->createAction(Action::ACTION_DELETE);
        }
    }

    public function actionFullHistory()
    {
        $collections = [553,
                        543,
                        542,
                        541,
                        590,
                        589,
                        546,
                        545,
                        544,
                        570,
                        569,
                        568,
                        567,
                        566,
                        565,
                        564,
                        582,
                        581,
                        580,
                        579,
                        578,
                        577,
                        573,
                        585,
                        584,
                        583,
                        536,
                        534,
                        533,
                        531,
                        529,
                        528,
                        527,
                        526,
                        525,
                        586,
                        572,
                        571,
                        540,
                        541,
                        530];

        foreach ($collections as $key => $id_collection)
        {
            $query = new Query();
            $records = $query->from('collection'.$id_collection)->indexBy('id_record')->all();

            $models = CollectionRecord::find()
                            ->select(['created_by','created_at','id_record'])
                            ->where(['id_record'=>array_keys($records)])
                            ->indexBy('id_record')
                            ->all();

            foreach ($records as $id_record=>$data)
            {
                $log = new RecordLog;

                $log->detachBehavior('ls');
                $log->detachBehavior('ba');

                $log->id_record = $id_record;
                $log->created_at = $models[$id_record]->created_at??null;
                $log->created_by = $models[$id_record]->created_by??null;
                $log->data = $data;
                $log->save();
            }
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

    public function actionUpdateCustomColumn($id_column)
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

    public function actionSubformFix()
    {
        $forms = Form::find()->where("is_template = 2 AND id_form NOT IN (SELECT id_form FROM form_element where id_form IS NOT NULL)")->all();

        echo count($forms);

        foreach ($forms as $key => $form)
        {
            foreach ($form->rows as $rkey => $row)
                foreach ($row->elements as $ekey => $element)
                {
                    if (!empty($element->input))
                    {
                        if (!empty($element->input->column))
                            $element->input->column->delete();

                        $element->input->delete();
                    }
                }

            $form->delete();
        }
    }

    public function actionFormFix()
    {
        $forms = Form::find()->where("name like '%Файл с листами%'")->all();

        foreach ($forms as $fkey => $form)
            foreach ($form->rows[0]->elements as $key => $element)
            {
                if (!empty($element->input) && $element->input->type==CollectionColumn::TYPE_FILE && strpos($element->input->fieldname,'file')!==false)
                {
                    foreach ($element->row->elements as $dkey => $deleteelement)
                    {
                        if ($deleteelement->id_element != $element->id_element)
                        {
                            if (!empty($deleteelement->input))
                            {
                                if (!empty($deleteelement->input->column))
                                    $deleteelement->input->column->delete();

                                $deleteelement->input->delete();
                            }

                            $deleteelement->delete();
                        }
                    }

                    $input = $element->input;

                    $curRow = FormElement::find()->where(['id_form'=>$element->row->id_form])->one();
                    $prevElement = null ;

                    if (!empty($curRow->row))
                    {
                        $prevRow = $curRow->row;
                        $prevRow = FormRow::find()->where(['ord'=>$prevRow->ord-1,'id_form'=>$prevRow->id_form])->one();

                        if (!empty($prevRow) && count($prevRow->elements)==1 && !empty($prevRow->elements[0]->content))
                        {
                            $prevElement = $prevRow->elements[0];
                            $input->label = $input->name = trim(str_replace('&nbsp;', ' ', strip_tags($prevElement->content)));
                            $prevElement->id_input = $input->id_input;
                        }
                    }

                    $options = $input->options;
                    $options['pagecount'] = 1;

                    $input->options = $options;

                    if ($input->save())
                    {
                        if ($prevElement)
                        {
                            $prevElement->content = '';
                            $prevElement->updateAttributes(['id_input','content']);
                            $curRow->row->delete();
                        }
                        else
                        {
                            echo "$input->id_form \r\n";
                        }
                    }

                    break;
                }
            }

        //$input = FormInput::find()->where('type = '.FormInput::TYPE_FILE)->andWhere('')
    }

    public function actionFixFilters()
    {
        $models = FormVisibleInput::find()->where('values IS NOT NULL')->all();

        foreach ($models as $key => $model)
        {
            $values = [];
            foreach ($model->values as $key => $value) {
                $values[] = str_replace("\r\n", '', trim($value));
            }

            $model->values = $values;
            $model->update();
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

    public function actionEventFix()
    {
        $collection = Collection::find()->where(['id_collection'=>379])->one();
        $records = $collection->getData([],true);

        foreach ($records as $data)
        {
            $period = [
                "begin" => $data['date_begin'],
                "end"=>$data['date_end'],
                "is_repeat"=>(int)$data['is_repeat'],
                "repeat_count"=>$data['repeat_count'],
                "repeat"=>$data['period'],
                "day_space"=>$data['between_day'],
                "week"=>$data['ch_week'],
                "week_space"=>$data['between'],
                "repeat_month"=>'',
                "month_days"=>"",
                "week_number"=>preg_replace('/\D/', '', $data['week_num']),
                "month_week"=>'',
            ];

            $record = CollectionRecord::findOne($data['id_record']);

            $record->data = [12741=>$record];
        }
    }

    public function actionCombineInstitution()
    {
        $collection = Collection::find()->where(['id_collection'=>88])->one();

        $mongoCollection = Yii::$app->mongodb->getCollection('collection'.$collection->id_collection);
        $mongoCollection->remove(['or',['id_record'=>null],['id_record'=>'']]);

        $data88 =  $collection->getData();

        $collection472 = Collection::find()->where(['id_collection'=>472])->one();

        if (!empty($collection472))
            $collection472 = $collection472->getData();
        else
            $collection472 = [];

        $collection346 = Collection::find()->where(['id_collection'=>346])->one();
        if (!empty($collection346))
            $collection346 = $collection346->getData();
        else
            $collection346 = [];

        foreach ($data88 as $id_record => $record)
        {
            $findRecord = CollectionRecord::findOne($id_record);

            $changedata = [];

            if ($record[1072] == '10')
                $changedata[1072] = '["2", "Автономное учреждение"]';
            else if ($record[1072] == '08')
                $changedata[1072] = '["1", "Казенное учреждение"]';
            else if ($record[1072] == '03')
                $changedata[1072] = '["3", "Бюджетное учреждение"]';

            if (!empty($record[1107]))
                $changedata[1107] = mb_strtoupper($record[1107]);

            if (!empty($record[1073]))
            {
                $output = [];
                $search = json_decode($record[1073],true);

                foreach ($search as $skey => $data)
                {
                    foreach ($collection472 as $ckey => $cdata)
                        if ($cdata[13041] == $data['fullname'])
                            $output[$cdata[13040]] = $data['fullname'];
                }

                $changedata[1073] = json_encode($output);
            }

            if (!empty($record[1074]))
            {
                $output = [];
                $search = json_decode($record[1074],true);

                foreach ($search as $skey => $data)
                {
                    foreach ($collection346 as $ckey => $cdata)
                        if ($cdata[1349] == $data['code'].' '.$data['name'])
                            $output[$cdata[1355]] = $data['code'].' '.$data['name'];
                }

                $changedata[1074] = json_encode($output);
            }

            print_r($changedata);
            die();

            $findRecord->data = $changedata;
            $findRecord->save();
        }

        die();

        $munic = Collection::find()->where(['id_collection'=>481])->one();

        $data481 = $munic->getData();

        $ccolumns = [
            13105=>13309,
            13106=>13310,
            13107=>13311,
            13108=>1070,
            13109=>1071,
            13110=>1101,
            13111=>1102,
            13112=>1103,
            13113=>13312,
            13114=>1072,
            13115=>1110,
            13116=>1108,
            13117=>1109,
            13118=>1107,
            13119=>1106,
            13120=>1105,
            13121=>1104,
            13122=>13314,
            13123=>1100,
            13124=>1099,
            13125=>1090,
            13126=>1089,
            13127=>1088,
            13128=>1087,
            13129=>1086,
            13130=>1085,
            13131=>1084,
            13132=>1083,
            13133=>1082,
            13134=>13315,
            13135=>1091,
            13136=>1092,
            13137=>1094,
            13138=>1095,
            13139=>1096,
            13140=>13316,
            13141=>1069,
            13142=>1077,
            13143=>1081,
            13144=>1080,
            13145=>1079,
            13146=>1078,
            13148=>1073,
            13149=>1076,
            13150=>1074,
            13151=>13317,
            13152=>13318,
            13153=>13319,
            13154=>13320,
            13155=>1065,
            13156=>13321,
            13157=>13322,
            13158=>13323,
            13159=>1068,
            13160=>13324,
            13161=>13325,
            13162=>13326,
            13163=>13327,
            13164=>13328,
            13165=>13329,
            13166=>1097,
            13167=>1098,
            13168=>13330,
            13169=>13331
        ];

        foreach ($data481 as $id_col => $data)
        {
            $prepareData = [];

            if ($data[13159]=='True')
                $prepareData[1068] = 0;

            $findRecord = false;
            foreach ($data88 as $id_record => $record)
            {
                if ($record[1070]==$data[13108] || $record[1101]==$data[13110])
                {
                    $findRecord = CollectionRecord::findOne($id_record);
                    break;
                }
            }

            if (empty($findRecord))
            {
                $findRecord = new CollectionRecord;
                $findRecord->id_collection = $collection->id_collection;
            }

            foreach ($data as $id_mun_column => $value)
            {
                $prepareData[$ccolumns[[$id_mun_column]]] = $value;
            }

            $findRecord->data = $prepareData;
            $findRecord->save();
        }
    }
}