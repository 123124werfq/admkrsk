<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_service_counter".
 *
 * @property int $id_counter
 * @property string|null $service_number
 * @property int|null $value
 * @property int|null $period
 * @property int|null $update_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class ServiceCounter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_service_counter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['value', 'period', 'update_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['value', 'period', 'update_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['service_number'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_counter' => 'Id Counter',
            'service_number' => 'Service Number',
            'value' => 'Value',
            'period' => 'Period',
            'update_at' => 'Update At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
