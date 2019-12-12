<?php

namespace common\models;

use common\models\Form;
use common\models\FormInput;
use Yii;
use \yii\base\DynamicModel ;

class FormDynamic extends DynamicModel
{
    public $inputs;
    public $form;

    //private $_properties;

    public function __construct($form, $data=null, $config = [])
    {
        $attributes = [];

        $this->form = $form;
        $this->inputs = FormInput::find()->where(['id_form' => $form->id_form])->indexBy('id_input')->all();

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

            $attributes['input'.$input->id_input] = (isset($data[$input->id_input]))?$data[$input->id_input]:'';
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

    public function prepareData($columnAsIndex=false, $post=null)
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
                    case CollectionColumn::TYPE_CHECKBOXLIST:
                        $data[$index] = json_encode($this->$attribute);
                        break;
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
                                            $collectionRecord = $input->collection->insertRecord($insertData->prepareData(true));

                                            if (!empty($collectionRecord->id_record))
                                                $ids[] = $collectionRecord->id_record;
                                        }
                                        else
                                            print_r($insertData->attributes);

                                    }
                                }
                            }
                            $data[$index] = json_encode($ids);
                        }
                        else
                            $data[$index] = json_encode($data[$index]);
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
                                $media = new Media;
                                $media->getImageAttributes($file['file_path'],$file);

                                if ($media->save())
                                    $media->saveFile();

                                $data[$index][] = $media->id_media;
                            }
                        }

                        $data[$index] = json_encode($data[$index]);

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

        return $data;
    }
}
