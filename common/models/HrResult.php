<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hr_contest_result".
 *
 * @property int $id_result
 * @property int $id_contest
 * @property int $id_profile
 * @property int $id_record
 * @property int $result
 * @property string $comment
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class HrResult extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_contest_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contest', 'id_profile', 'id_record', 'result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_contest', 'id_profile', 'id_record', 'result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
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
            'id_contest' => 'Id Contest',
            'id_profile' => 'Id Profile',
            'id_record' => 'Id Record',
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
