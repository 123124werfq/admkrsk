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
        $this->inputs = FormInput::find()->where(['id_form' => $form->id_form])->all();

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
        	$this->addRule(['input'.$input->id_input], 'safe');
    }

    public function prepareData($post)
    {
        foreach ($this->attributes as $key => $value)
        {
            # code...
        }
    }
}
