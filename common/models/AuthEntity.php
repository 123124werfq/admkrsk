<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%auth_entity}}".
 *
 * @property int $id_user
 * @property int $id_user_group
 * @property int $entity_id
 * @property string $class
 *
 * @property User $user
 * @property UserGroup $userGroup
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
            [['id_user', 'id_user_group'], 'default', 'value' => null],
            [['id_user', 'entity_id', 'id_user_group'], 'integer'],
            [['entity_id', 'class'], 'required'],
            [['class'], 'string', 'max' => 255],
            [['id_user', 'id_user_group', 'entity_id', 'class'], 'unique', 'targetAttribute' => ['id_user', 'id_user_group', 'entity_id', 'class']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'id_user' => 'Пользователь',
            'id_user_group' => 'Группа пользователей',
            'entity_id' => 'ID объекта',
            'class' => 'Класс',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(UserGroup::class, ['id_user_group' => 'id_user_group']);
    }

    /**
     * @param string $className
     * @return array
     */
    public static function getEntityIds($className)
    {
        return self::find()->select('entity_id')->andWhere([
            'or',
            [
                'class' => $className,
                'id_user' => Yii::$app->user->id,
            ],
            [
                'class' => $className,
                'id_user_group' => Yii::$app->user->identity->groupIds,
            ]
        ])->column();
    }
}
