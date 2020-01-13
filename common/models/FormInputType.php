<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

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
    use SoftDeleteTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Тип поля';
    const VERBOSE_NAME_PLURAL = 'Типы полей';
    const TITLE_ATTRIBUTE = 'name';

    public $class, $placeholder, $style, $allow;

    public $access_user_ids;
    public $access_user_group_ids;

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

            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['access_user_group_ids', 'each', 'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']],
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

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.formInputType',
            ],
        ];
    }
}
