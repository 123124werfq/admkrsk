<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbl_user_user_group".
 *
 * @property int $id_user
 * @property int $id_user_group
 *
 * @property UserGroup $userGroup
 * @property User $user
 */
class UserUserGroup extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dbl_user_user_group';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_user_group'], 'required'],
            [['id_user', 'id_user_group'], 'default', 'value' => null],
            [['id_user', 'id_user_group'], 'integer'],
            [['id_user_group'], 'exist', 'skipOnError' => true, 'targetClass' => UserGroup::class, 'targetAttribute' => ['id_user_group' => 'id_user_group']],
            [['id_user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['id_user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_user' => 'Id User',
            'id_user_group' => 'Id User Group',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserGroup()
    {
        return $this->hasOne(UserGroup::class, ['id_user_group' => 'id_user_group']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}
