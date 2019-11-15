<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_house".
 *
 * @property int $id_house
 * @property string $houseguid
 * @property string $postalcode
 * @property string $name
 * @property string $fullname
 *
 * @property FiasHouse $house
 * @property Region $region
 * @property Subregion $subregion
 * @property City $city
 * @property District $district
 * @property Street $street
 */
class House extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_house';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['houseguid'], 'string'],
            [['postalcode', 'name', 'fullname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_house' => 'Id House',
            'houseguid' => 'Houseguid',
            'postalcode' => 'Почтовый индекс',
            'name' => 'Дом',
            'fullname' => 'Полный адрес',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(FiasHouse::class, ['houseguid' => 'houseguid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::class, ['id_region' => 'id_region']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubregion()
    {
        return $this->hasOne(Subregion::class, ['id_subregion' => 'id_subregion']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::class, ['id_city' => 'id_city']);
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

    /**
     * @return string
     */
    public function getBreadcrumbsLabel()
    {
        return 'Адреса';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->fullname;
    }
}
