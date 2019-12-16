<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hr_vote".
 *
 * @property int $id_vote
 * @property int $id_expert
 * @property int $id_profile
 * @property int $id_record
 * @property int $value
 * @property string $comment
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class HrVote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_vote';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_expert', 'id_profile', 'id_record', 'id_contest', 'value', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_expert', 'id_profile', 'id_record', 'id_contest', 'value', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
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
            'id_record' => 'Id Record',
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
