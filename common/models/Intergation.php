<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_intergation".
 *
 * @property int $id_integration
 * @property int $system
 * @property int $direction
 * @property int $status
 * @property string $operation
 * @property string $description
 * @property string $data_type
 * @property string $data
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Intergation extends \yii\db\ActiveRecord
{

    public const SYSTEM_SMEV = 1;
    public const SYSTEM_SED = 2;
    public const SYSTEM_SUO = 3;

    public const DIRECTION_OUTPUT = 1;
    public const DIRECTION_INPUT = 1;

    public const STATUS_OK = 1;
    public const STATUS_ERROR = 88;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_intergation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system', 'direction', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['system', 'direction', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['data'], 'string'],
            [['operation', 'description', 'data_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_integration' => 'Id Integration',
            'system' => 'System',
            'direction' => 'Direction',
            'status' => 'Status',
            'operation' => 'Operation',
            'description' => 'Description',
            'data_type' => 'Data Type',
            'data' => 'Data',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
