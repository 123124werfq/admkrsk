<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_target".
 *
 * @property int $id_target
 * @property int $id_service
 * @property int $id_form
 * @property string $name
 * @property string $reestr_number
 * @property int $state
 * @property int $modified_at
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ServiceTarget extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_target';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_service', 'id_form', 'state', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_service', 'id_form', 'state', 'modified_at', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 500],
            [['reestr_number','target','place','target_code','service_code','obj_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_target' => 'ID',
            'id_service' => 'Услуга',
            'id_form' => 'Форма',
            'name' => 'Название',
            'reestr_number' => 'Реестровый номер',
            'state' => 'Активно',
            'target'=> 'Код',
            'target_code'=> 'Код цели',
            'place'=>'Место',
            'service_code'=> 'Код сервиса',
            'obj_name' => 'Наименование объекта',
            'modified_at' => 'Modified At',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id_service' => 'id_service']);
    }

    public function getForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }
}
