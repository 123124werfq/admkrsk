<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_appeal_form".
 *
 * @property int|null $id_apeal
 * @property int|null $id_form
 * @property int|null $id_record_firm
 * @property int|null $id_record_category
 * @property int|null $id_service
 */
class ServiceAppealForm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_appeal_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_form', 'id_record_firm', 'id_record_category', 'id_service'], 'default', 'value' => null],
            [['id_form', 'id_record_firm', 'id_record_category', 'id_service'], 'required'],
            [['id_form', 'id_record_firm', 'id_record_category', 'id_service'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_form' => 'Форма',
            'id_record_firm' => 'Организация',
            'id_record_category' => 'Категория',
            'id_service' => 'Услуга',
        ];
    }


    public function getForm()
    {
        return $this->hasOne(Form::class, ['id_form' => 'id_form']);
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id_service' => 'id_service']);
    }

    public function getCategory()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_category']);
    }

    public function getFirm()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_firm']);
    }
}
