<?php

namespace common\models;

use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "form_input_type".
 *
 * @property int $id_type
 * @property int $id_collection
 * @property string $name
 * @property string $regexp
 * @property string $options
 * @property int $type
 * @property int $esia
 * @property string $values
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class FormInputType extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Тип поля';
    const VERBOSE_NAME_PLURAL = 'Типы полей';
    const TITLE_ATTRIBUTE = 'name';

    public $class, $placeholder, $style, $allow;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_input_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'type', 'esia', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_collection', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'required'],
            [['options', 'values', 'class', 'placeholder', 'style', 'allow','esia','service_attribute'], 'string'],
            [['name', 'regexp'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_type' => 'ID',
            'id_collection' => 'Коллекция',
            'name' => 'Название',
            'regexp' => 'Регулярное выражение',
            'options' => 'Опции',
            'type' => 'Тип',
            'esia' => 'Связать с ЕСИА',
            'service_attribute' => 'Связать с полем услуги',
            'values' => 'Значения',
            'class' => 'Класс поля',
            'placeholder' => 'Подсказка',
            'style'=>'Стили поля',
            'allow'=>'Допустимые типы файлов',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
