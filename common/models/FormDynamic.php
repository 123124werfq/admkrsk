<?php

namespace common\models;

use common\models\Form;
use common\models\FormInput;
use common\models\House;
use common\models\CollectionRecord;
use Yii;
use \yii\base\DynamicModel ;

class FormDynamic extends DynamicModel
{
    public $inputs;
    public $form;
    public $data;

    //private $_properties;

    public function __construct($form, $data=null, $config = [])
    {
        $attributes = [];

        $this->form = $form;
        $this->inputs = $form->getInputs()->indexBy('id_input')->all();
        $this->data = $data;

        foreach ($this->inputs as $input)
        {
            // заполняем данные их ЕСИА
            if (!Yii::$app->user->isGuest && !empty($input->id_type) && !empty($input->typeOptions->esia))
            {
                $esia = Yii::$app->user->identity->esiainfo;
                $attr = $input->typeOptions->esia;

                if (!empty($esia->$attr))
                    $data[$input->id_input] = $esia->$attr;
            }

            $attributes['input'.$input->id_input] = (isset($data[$input->alias]))?$data[$input->alias]:'';
        }

        parent::__construct($attributes, $config);

        foreach ($this->inputs as $input)
        {
            switch ($input->type) {
                case CollectionColumn::TYPE_INTEGER:
                    $this->addRule(['input'.$input->id_input], 'number');
                    break;
                case CollectionColumn::TYPE_INPUT:
                    $this->addRule(['input'.$input->id_input], 'string');
                    break;
                default:
                    $this->addRule(['input'.$input->id_input], 'safe');
                    break;
            }
        }
    }

    public function loadDataFromRecord($data)
    {
        foreach ($this->inputs as $key => $input)
        {
            if (isset($data[$input->id_column]))
            {
                $var = 'input'.$input->id_input;
                $this->$var = $data[$input->id_column];
            }
        }
    }

    public function prepareData($columnAsIndex=true, $post=null)
    {
        $data = [];

        foreach ($this->inputs as $key => $input)
        {
            $attribute = 'input'.$input->id_input;

            if (isset($this->$attribute))
            {
                $index = ($columnAsIndex)?$input->id_column:$input->id_input;

                switch ($input->type) {
                    case CollectionColumn::TYPE_INTEGER:
                        $data[$index] = (float)$this->$attribute;
                        break;
                    case CollectionColumn::TYPE_INPUT:
                        $data[$index] = trim((string)$this->$attribute);
                        break;
                    case CollectionColumn::TYPE_JSON:
                        $data[$index] = json_encode($this->$attribute);
                        break;

                    case CollectionColumn::TYPE_ADDRESS:

                        $value = $this->$attribute;

                        $empty = [
                                'country'=>'',
                                'id_country'=>'',
                                'region'=>'',
                                'id_region'=>'',
                                'subregion'=>'',
                                'id_subregion'=>'',
                                'city'=>'',
                                'id_city'=>'',
                                'district'=>'',
                                'id_district'=>'',
                                'street'=>'',
                                'id_street'=>'',
                                'house'=>'',
                                'id_house'=>'',
                                'houseguid'=>'',
                                'lat'=>'',
                                'lon'=>'',
                                'postcode'=>''
                            ];

                        if (!empty($value['house']))
                        {
                            $address = House::find()->filterWhere([
                                'id_country'=>!empty($value['country'])?(int)$value['country']:null,
                                'id_region'=>!empty($value['region'])?(int)$value['region']:null,
                                'id_subregion'=>!empty($value['subregion'])?(int)$value['subregion']:null,
                                'id_city'=>!empty($value['city'])?(int)$value['city']:null,
                                'id_district'=>!empty($value['district'])?(int)$value['district']:null,
                                'id_street'=>!empty($value['street'])?(int)$value['street']:null,
                                'id_house'=>!empty($value['house'])?(int)$value['house']:null,
                            ])->one();

                            if (!empty($address))
                            {
                                $value = [
                                    'country'=>$address->country->name??'',
                                    'id_country'=>$address->id_country??'',
                                    'region'=>$address->region->name??'',
                                    'id_region'=>$address->id_region??'',
                                    'subregion'=>$address->subregion->name??'',
                                    'id_subregion'=>$address->id_subregion??'',
                                    'city'=>$address->city->name??'',
                                    'id_city'=>$address->id_city??'',
                                    'district'=>$address->district->name??'',
                                    'id_district'=>$address->id_district??'',
                                    'street'=>$address->street->name??'',
                                    'id_street'=>$address->id_street??'',
                                    'house'=>$address->name??'',
                                    'id_house'=>$address->id_house??'',
                                    'houseguid'=>$address->houseguid??'',
                                    'lat'=>$address->lat??'',
                                    'lon'=>$address->lon??'',
                                    'postalcode'=>$address->postalcode??''
                                ];
                            }
                        }

                        if (empty($address))
                        {
                            if (!empty($value['country']))
                            {
                                if (is_numeric($value['country']))
                                {
                                    $addModel = Country::findOne($value['country']);

                                    $empty['country'] = $addModel->name??'';
                                    $empty['id_country'] = $addModel->id_country??'';
                                }
                                else
                                    $empty['country'] = $value['country'];
                            }

                            if (!empty($value['region']))
                            {

                                if (is_numeric($value['region']))
                                {
                                    $addModel = Region::findOne($value['region']);

                                    $empty['region'] = $addModel->name??'';
                                    $empty['id_region'] = $addModel->id_region??'';
                                }
                                else
                                    $empty['region'] = $value['region'];
                            }

                            if (!empty($value['subregion']))
                            {
                                if (is_numeric($value['subregion']))
                                {
                                    $addModel = Subregion::findOne($value['subregion']);

                                    $empty['subregion'] = $addModel->name??'';
                                    $empty['id_subregion'] = $addModel->id_subregion??'';
                                }
                                else
                                    $empty['subregion'] = $value['subregion'];
                            }

                            if (!empty($value['city']))
                            {
                                if (is_numeric($value['city']))
                                {
                                    $addModel = City::findOne($value['city']);

                                    $empty['city'] = $addModel->name??'';
                                    $empty['id_city'] = $addModel->id_city??'';
                                }
                                else
                                    $empty['city'] = $value['city'];
                            }

                            if (!empty($value['district']))
                            {
                                if (is_numeric($value['district']))
                                {
                                    $addModel = District::findOne($value['district']);

                                    $empty['district'] = $addModel->name??'';
                                    $empty['id_district'] = $addModel->id_district??'';
                                }
                                else
                                    $empty['district'] = $value['district'];
                            }

                            if (!empty($value['street']))
                            {
                                if (is_numeric($value['street']))
                                {
                                    $addModel = Street::findOne($value['street']);

                                    $empty['street'] = $addModel->name??'';
                                    $empty['id_street'] = $addModel->id_street??'';
                                }
                                else
                                    $empty['street'] = $value['street'];
                            }

                            if (!empty($value['house']))
                            {
                                if (is_numeric($value['house']))
                                {
                                    $addModel = Street::findOne($value['house']);

                                    $empty['house'] = $addModel->name??'';
                                    $empty['id_house'] = $addModel->id_house??'';
                                }
                                else
                                    $empty['house'] = $value['house'];
                            }

                            if (!empty($value['coords'][0]))
                                $empty['lat'] = $value['coords'][0];

                            if (!empty($value['coords'][1]))
                                $empty['lon'] = $value['coords'][1];

                            if (!empty($value['poastacode']))
                                $empty['poastacode'] = $value['poastacode'];

                            $value = $empty;
                        }

                        $data[$index] = $value;

                        break;
                    /*case CollectionColumn::TYPE_CHECKBOXLIST:
                        $data[$index] = json_encode($this->$attribute);
                        break;*/
                    case CollectionColumn::TYPE_COLLECTIONS:
                        if ($input->options['accept_add'])
                        {
                            $ids = [];

                            if (!empty($_POST['input'.$input->id_input]) && is_array($_POST['input'.$input->id_input]))
                            {
                                foreach ($_POST['input'.$input->id_input] as $key => $group)
                                {
                                    if (!empty($_POST['FormDynamic'][$group]))
                                    {
                                        $insertData = new FormDynamic($input->collection->form);
                                        $insertData->attributes = $_POST['FormDynamic'][$group];

                                        if ($insertData->validate())
                                        {
                                            $collectionRecord = null;
                                            $prepareData  = $insertData->prepareData(true);

                                            if (!empty($_POST['input'.$input->id_input.'_id_record'][$key]))
                                            {
                                                $collectionRecord = CollectionRecord::findOne((int)$_POST['input'.$input->id_input.'_id_record'][$key]);
                                            }

                                            if (empty($collectionRecord))
                                                $collectionRecord = $input->collection->insertRecord($prepareData);
                                            else
                                            {
                                                $collectionRecord->data = $prepareData;
                                                $collectionRecord->save();
                                            }

                                            if (!empty($collectionRecord->id_record))
                                                $ids[] = $collectionRecord->id_record;
                                        }
                                        else
                                            print_r($insertData->attributes);

                                    }
                                }
                            }
                            //$data[$index] = json_encode($ids);
                            $data[$index] = $ids;
                        }
                        else
                        {
                            /*$records = CollectionRecord::find()->where(['id_record' => $this->$attribute])->all();

                            $output = [];
                            foreach ($records as $key => $record)
                            {
                                $data = $record->getData(false,[$input->id_collection_column]);
                                $output[$record->id_record] = $data[$input->id_collection_column];
                            }

                            var_dump($output);
                            die();

                            $data[$index] = $output;*/
                            $data[$index] = $this->$attribute;
                        }
                        break;
                    case CollectionColumn::TYPE_DATE:
                    case CollectionColumn::TYPE_DATETIME:
                        $data[$index] = strtotime($this->$attribute);
                        break;
                    case CollectionColumn::TYPE_FILE:
                    case CollectionColumn::TYPE_IMAGE:

                        $data[$index] = [];

                        if (!empty($this->$attribute) && is_array($this->$attribute))
                        {
                            foreach ($this->$attribute as $key => $file)
                            {
                                if (empty($file['id_media']))
                                {
                                    $media = new Media;
                                    $media->getImageAttributes($file['file_path'],$file);

                                    if ($media->save())
                                        $media->saveFile();

                                    $data[$index][] = $media->id_media;
                                }
                                else
                                    $data[$index][] = (int)$file['id_media'];
                            }
                        }

                        $data[$index] = $data[$index];

                        break;
                    default:

                        if (is_string($this->$attribute))
                            $data[$index] = trim($this->$attribute);
                        else
                            $data[$index] = $this->$attribute;

                        break;
                }
            }
        }

        if (!empty($this->data))
        {
            $columns = $this->form->collection->getColumns()->andWhere(['alias'=>array_keys($this->data)])->indexBy('alias')->all();
            
            foreach ($columns as $alias => $column)
                $data[$column->id_column] = $this->data[$alias];
        }

        return $data;
    }
}
