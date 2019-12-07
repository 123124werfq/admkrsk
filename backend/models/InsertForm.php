<?php

namespace backend\models;

use Yii;
use yii\base\Model;

class InsertForm extends Model
{
    public $id_form_parent, $id_form, $prefix;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_visibleinput';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'id_form_parent'], 'default', 'value' => null],
            [['prefix'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_form' => 'Форма',
            'id_form_parent' => 'Форма куда вставляется',
            'prefix' => 'Префикс для переменных',
        ];
    }
}
