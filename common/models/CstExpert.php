<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "cst_expert".
 *
 * @property int $id_expert
 * @property int|null $id_user
 * @property int|null $state
 * @property string|null $comment
 * @property int|null $id_media
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class CstExpert extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cst_expert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'state', 'id_media', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'state', 'id_media', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_expert' => 'Id Expert',
            'id_user' => 'Id User',
            'state' => 'State',
            'comment' => 'Comment',
            'id_media' => 'Id Media',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            /*
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.news',
            ]
            */
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getName()
    {
        return $this->user->getUsername();
    }    
}
