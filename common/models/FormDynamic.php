<?php

namespace common\models;

use common\models\Form;
use common\models\FormInput;
use Yii;
use \yii\base\DynamicModel ;


class FormDynamic extends DynamicModel
{
    //private $_properties;

    public function __construct($form, $data=null, $config = [])
    {
        $attributes = [];

        $inputs = FormInput::find()->where(['id_form' => $form->id_form])->all();

        foreach ($inputs as $input)
            $attributes['input'.$input->id_input] = (isset($data[$input->id_input]))?$data[$input->id_input]:'1';

        parent::__construct($attributes, $config);
    }
}
