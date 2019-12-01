<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form_visibleinput".
 *
 * @property int $id
 * @property int $id_input
 * @property int $id_input_visible
 * @property string $values
 */
class FormVisibleinput extends \yii\db\ActiveRecord
{
    public $visibleInputs;
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
            [['id_input', 'id_input_visible'], 'default', 'value' => null],
            [['id_input', 'id_input_visible'], 'integer'],
            [['values'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_input' => 'Id Input',
            'id_input_visible' => 'Id Input Visible',
            'values' => 'Values',
        ];
    }

    public function getVisibleInput()
    {
        return $this->hasOne(FormInput::class, ['id_input' => 'id_input_visible']);
    }
}
