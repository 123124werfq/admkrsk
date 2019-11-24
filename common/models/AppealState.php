<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "appeal_state".
 *
 * @property int $id_state
 * @property int $id_request
 * @property string $state
 * @property int $archive
 * @property string $workflow_message
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class AppealState extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appeal_state';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_request', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_request', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['state', 'workflow_message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_state' => 'Id State',
            'id_request' => 'Id Request',
            'state' => 'State',
            'archive' => 'Archive',
            'workflow_message' => 'Workflow Message',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
