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
            [['user_id', 'entity_id', 'class'], 'required'],
            [['user_id', 'entity_id'], 'integer'],
            [['class'], 'string', 'max' => 255],
            [['user_id', 'entity_id', 'class'], 'unique', 'targetAttribute' => ['user_id', 'entity_id', 'class']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'Пользователь',
            'entity_id' => 'ID объекта',
            'class' => 'Класс',
        ];
    }
}
