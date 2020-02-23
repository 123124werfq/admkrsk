<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cst_contest_result".
 *
 * @property int $id_result
 * @property int|null $id_record_contest
 * @property int|null $id_profile
 * @property int|null $type
 * @property int|null $result
 * @property string|null $comment
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class CstContestResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cst_contest_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_record_contest', 'id_profile', 'type', 'result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_record_contest', 'id_profile', 'type', 'result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['comment'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_result' => 'Id Result',
            'id_record_contest' => 'Id Record Contest',
            'id_profile' => 'Id Profile',
            'type' => 'Type',
            'result' => 'Result',
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
