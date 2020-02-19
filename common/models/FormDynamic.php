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
    public $postData;

    //private $_properties;

    public function __construct($form, $data=null, $config = [])
    {
        $attributes = [];

        $this->form = $form;
        $this->inputs = $form->getInputs()->indexBy('id_input')->all();

        foreach ($this->inputs as $input)
        {
            // заполняем данные их ЕСИА
            if (!Yii::$app->user->isGuest && !empty($input->id_type) && !empty($input->typeOptions->esia))
            {
                $esia = Yii::$app->user->identity->esiainfo;
                $attr = $input->typeOptions->esia;

                if (!empty($esia->$attr))
                    $data[$input->fieldname] = $esia->$attr;
            }

            if (!empty($data[$input->fieldname]))
                $attributes['input'.$input->id_input] = $data[$input->fieldname];
            else
                $attributes['input'.$input->id_input] = '';
        }

        $this->data = $data;

        if ($form->captcha)
            $attributes['captcha'] = '';

        parent::__construct($attributes, $config);

        if ($form->captcha)
            $this->addRule(['captcha'], 'captcha');

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

                switch ($input->type)
                {
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
                                    'room'=>'',
                                    'houseguid'=>'',
                                    'lat'=>'',
                                    'lon'=>'',
                                    'postalcode'=>''
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
                                $data[$index] = $address->getArrayData();
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

                            if (!empty($value['postalcode']))
                                $empty['postalcode'] = $value['postalcode'];

                            $data[$index] = $empty;
                        }

                        if (!empty($value['room']))
                            $data[$index]['room'] = $value['room'];

                        break;
                    /*case CollectionColumn::TYPE_CHECKBOXLIST:
                        $data[$index] = json_encode($this->$attribute);
                        break;*/
                    case CollectionColumn::TYPE_COLLECTIONS:

                        // если разрешили добавлять
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
                                    }
                                }
                            }

                            $data[$index] = $ids;
                        }
                        else
                            $data[$index] = $this->$attribute;

                        break;
                    case CollectionColumn::TYPE_DISTRICT:
                    case CollectionColumn::TYPE_STREET:
                    //case CollectionColumn::TYPE_COUNTRY:
                    case CollectionColumn::TYPE_CITY:
                    case CollectionColumn::TYPE_REGION:
                    case CollectionColumn::TYPE_SUBREGION:
                        /*$output[$search_index] = $input->column->getValueByType($value);
                        $output[$value_index] = [
                            'value'=>$value,
                            'label'=>$output['search'],
                        ];*/
                        $data[$index] = $this->$attribute;
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

                                    if (isset($file['pagecount']))
                                        $media->pagecount = (int)$file['pagecount'];

                                    if ($media->save())
                                    {
                                        $media->saveFile();

                                        $data[$index][] = [
                                            'id'=>$media->id_media,
                                            'name'=>$media->name,
                                            'size'=>$media->size,
                                        ];
                                    }
                                }
                                else
                                {
                                    $media = Media::findOne((int)$file['id_media']);

                                    if (isset($file['pagecount']))
                                        $media->pagecount = (int)$file['pagecount'];

                                    if (!empty($media))
                                        $data[$index][] = [
                                            'id'=>$media->id_media,
                                            'name'=>$media->name,
                                            'size'=>$media->size,
                                        ];
                                }
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

        if (!empty(Yii::$app->request->post('postData')))
        {
            $postData = json_decode(Yii::$app->request->post('postData'),true);

            if (!empty($postData))
            {
                $columns = $this->form->collection->getColumns()->andWhere(['alias'=>array_keys($postData)])->indexBy('alias')->all();

                foreach ($columns as $alias => $column)
                    $data[$column->id_column] = $postData[$alias];
            }
        }

        return $data;
    }

    protected function getRecordLabelsByID($ids,$input)
    {
        $mongoLabels = [];

        if (!empty($ids))
        {
            $labels = (new \yii\db\Query())
                ->select(['value', 'id_record','id_column'])
                ->from('db_collection_value')
                ->where([
                    'id_record' => $ids,
                    'id_column'=>$input->id_collection_column
                ])->all();

            foreach ($labels as $lkey => $data)
                $mongoLabels[$data['id_record']] = $data['value'];
        }

        return $mongoLabels;
    }
}
