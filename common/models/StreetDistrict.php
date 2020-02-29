<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "mapl_street_district".
 *
 * @property int $id_street
 * @property int $id_district
 *
 * @property District $district
 * @property Street $street
 */
class StreetDistrict extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mapl_street_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_street', 'id_district'], 'required'],
            [['id_street', 'id_district'], 'integer'],
            [['id_district'], 'exist', 'skipOnError' => true, 'targetClass' => District::class, 'targetAttribute' => ['id_district' => 'id_district']],
            [['id_street'], 'exist', 'skipOnError' => true, 'targetClass' => Street::class, 'targetAttribute' => ['id_street' => 'id_street']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_street' => 'Id Street',
            'id_district' => 'Id District',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistrict()
    {
        return $this->hasOne(District::class, ['id_district' => 'id_district']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStreet()
    {
        return $this->hasOne(Street::class, ['id_street' => 'id_street']);
    }
}
