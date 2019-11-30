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
    public $alias;
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
            [['id_form', 'id_type', 'id_collection', 'visibleInput', 'size', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','required','type'], 'integer'],
            [['name', 'type'], 'required'],
            [['values', 'hint','label'], 'string'],
            [['visibleInputValue','options'],'safe'],
            [['name', 'fieldname','alias'], 'string', 'max' => 255],
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
            'type' => 'Тип поля',
            'id_type' => 'Пресет поля',
            'required' => 'Обязательно',
            'id_collection' => 'Коллекция',
            'label' => 'Подпись',
            'name' => 'Название',
            'hint' => 'Пояснение',
            'fieldname' => 'Псевдоним переменной',
            'visibleInput' => 'Зависимость видимости',
            'visibleInputValue' => 'Значение',
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

    public function beforeValidate()
    {
        if (!empty($this->visibleInputValue) && !is_array($this->visibleInputValue))
            $this->visibleInputValue = [$this->visibleInputValue];

        return parent::beforeValidate();
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
        {
            $vars = explode(';', $this->values);

            foreach ($vars as $key => $value) {
                $values[$value] = $value;
            }
        }

        return $values;
    }

    public function getElement()
    {
        return $this->hasOne(FormElement::class, ['id_input' => 'id_input']);
    }

    public function getColumn()
    {
        return $this->hasOne(CollectionColumn::class, ['id_column' => 'id_column']);
    }

    public function getTypeOptions()
    {
        return $this->hasOne(FormInputType::class, ['id_type' => 'id_type']);
    }

    public function getVisibleInputModel()
    {
        return $this->hasOne(FormInput::class, ['id_input' => 'visibleInput']);
    }

}
