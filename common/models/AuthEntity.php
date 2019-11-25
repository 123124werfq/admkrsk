<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%auth_entity}}".
 *
 * @property int $user_id
 * @property int $entity_id
 * @property string $class
 */
class AuthEntity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auth_entity}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'id_user_group'], 'default', 'value' => null],
            [['user_id', 'entity_id', 'id_user_group'], 'integer'],
            [['entity_id', 'class'], 'required'],
            [['class'], 'string', 'max' => 255],
            [['user_id', 'id_user_group', 'entity_id', 'class'], 'unique', 'targetAttribute' => ['user_id', 'id_user_group', 'entity_id', 'class']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'user_id' => 'Пользователь',
            'id_user_group' => 'Группа пользователей',
            'entity_id' => 'ID объекта',
            'class' => 'Класс',
        ];
    }
}
