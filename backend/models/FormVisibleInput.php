<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "form_visibleinput".
 *
 * @property int $id
 * @property int|null $id_input
 * @property int|null $id_input_visible
 * @property string|null $values
 */
class FormVisibleInput extends \yii\db\ActiveRecord
{
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
            [['values'], 'string'],
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
}
