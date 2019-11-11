<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form_input".
 *
 * @property int $id_input
 * @property int $id_form
 * @property int $id_type
 * @property int $id_collection
 * @property string $name
 * @property string $fieldname
 * @property int $visibleInput
 * @property string $visibleInputValue
 * @property string $values
 * @property int $size
 * @property string $options
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class FormInput extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_input';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'id_type', 'id_collection', 'visibleInput', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','label'], 'default', 'value' => null],
            [['id_form', 'id_type', 'id_collection', 'visibleInput', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','required'], 'integer'],
            [['name', 'id_type'], 'required'],
            [['values', 'options','hint','label'], 'string'],
            [['name', 'fieldname', 'visibleInputValue'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_input' => 'Id Input',
            'id_form' => 'Форма',
            'id_type' => 'Тип поля',
            'required' => 'Обязательно',
            'id_collection' => 'Коллекция',
            'label' => 'Подпись',
            'name' => 'Название',
            'hint' => 'Пояснение',
            'fieldname' => 'Название переменной',
            'visibleInput' => 'Visible Input',
            'visibleInputValue' => 'Visible Input Value',
            'values' => 'Значения',
            'size' => 'Размер',
            'options' => 'Опции',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getArrayValues($model=null)
    {
        if (!empty($this->type->service_attribute))
        {
            $values = Service::getAttributeValues($this->type->service_attribute,$model);

            return $values;
        }

        $values = [];

        if (!empty($this->values))
            $values = explode(';', $this->values);

        return $values;
    }

    public function getType()
    {
        return $this->hasOne(FormInputType::class, ['id_type' => 'id_type']);
    }
}
