<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cst_vote".
 *
 * @property int $id_vote
 * @property int|null $id_expert
 * @property int|null $id_profile
 * @property int|null $type
 * @property int|null $value
 * @property string|null $comment
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class CstVote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cst_vote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_expert', 'id_profile', 'type', 'value', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_expert', 'id_profile', 'type', 'value', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_vote' => 'Id Vote',
            'id_expert' => 'Id Expert',
            'id_profile' => 'Id Profile',
            'type' => 'Type',
            'value' => 'Value',
            'comment' => 'Comment',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
