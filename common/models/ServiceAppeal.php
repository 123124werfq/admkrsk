<?php

// Модель для сбора информации о подданых заявках на получение муниципальных услуг

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_appeal".
 *
 * @property int $id_appeal
 * @property int $id_user
 * @property int $id_service
 * @property string $state
 * @property int $date
 * @property string $data
 * @property int $archive
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ServiceAppeal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_appeal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_service', 'id_record', 'date', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_target'], 'default', 'value' => null],
            [['id_user', 'id_service', 'id_record', 'date', 'archive', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'id_target'], 'integer'],
            [['state'], 'required'],
            [['data', 'number_internal', 'number_system', 'number_common'], 'string'],
            [['state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_appeal' => 'Id Appeal',
            'id_user' => 'Id User',
            'id_service' => 'Id Service',
            'id_record' => 'ID record',
            'state' => 'State',
            'date' => 'Date',
            'data' => 'Data',
            'archive' => 'Archive',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getCollectionRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record']);
    }

     public function getTarget()
    {
        return $this->hasOne(ServiceTarget::class, ['id_target' => 'id_target']);
    }
}
