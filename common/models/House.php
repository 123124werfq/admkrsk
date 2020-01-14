<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_house".
 *
 * @property int $id_house
 * @property string $houseguid
 * @property string $postalcode
 * @property string $id_country
 * @property string $id_region
 * @property string $id_subregion
 * @property string $id_city
 * @property string $id_district
 * @property string $id_street
 * @property string $name
 * @property float $lat
 * @property float $lon
 * @property string $fullname
 * @property bool $is_updatable
 * @property int $update_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property array $location
 *
 * @property FiasHouse $house
 * @property Country $country
 * @property Region $region
 * @property Subregion $subregion
 * @property City $city
 * @property District $district
 * @property Street $street
 */
class House extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Адрес';
    const VERBOSE_NAME_PLURAL = 'Адреса';
    const TITLE_ATTRIBUTE = 'fullName';

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
            [['is_updatable'], 'boolean'],
            [['lat', 'lon'], 'number'],
            [['id_country', 'id_region', 'id_subregion', 'id_city', 'id_district', 'id_street'], 'default', 'value' => null],
            [['id_country', 'id_region', 'id_subregion', 'id_city', 'id_district', 'id_street'], 'integer'],
            [['id_country'], 'exist', 'targetClass' => Country::class, 'targetAttribute' => 'id_country'],
            [['id_region'], 'exist', 'targetClass' => Region::class, 'targetAttribute' => 'id_region'],
            [['id_subregion'], 'exist', 'targetClass' => Subregion::class, 'targetAttribute' => 'id_subregion'],
            [['id_city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id_city'],
            [['id_district'], 'exist', 'targetClass' => District::class, 'targetAttribute' => 'id_district'],
            [['id_street'], 'exist', 'targetClass' => Street::class, 'targetAttribute' => 'id_street'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_house' => '#',
            'houseguid' => 'Houseguid',
            'postalcode' => 'Почтовый индекс',
            'id_country' => 'Страна',
            'id_region' => 'Регион',
            'id_subregion' => 'Район',
            'id_city' => 'Город',
            'id_district' => 'Район города',
            'id_street' => 'Улица',
            'name' => 'Дом',
            'lat' => 'Широта',
            'lon' => 'Долгота',
            'fullname' => 'Полный адрес',
            'is_updatable' => 'Обновлять с ФИАС',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        $this->fullname = $this->getFullName();

        return parent::beforeSave($insert);
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
    public function getCountry()
    {
        return $this->hasOne(Country::class, ['id_country' => 'id_country']);
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
    public function getFullName()
    {
        return ($this->postalcode ? $this->postalcode . ', ' : null) .
            $this->country->name . ', ' .
            $this->region->name . ', ' .
            ($this->subregion ? $this->subregion->name . ', ' : null) .
            $this->city->name . ', ' .
            ($this->district ? $this->district->name . ', ' : null) .
            $this->street->name . ', ' .
            $this->name;
    }

    /**
     * @return array
     */
    public function getLocation()
    {
        return [
            'lat' => $this->lat,
            'lon' => $this->lon,
        ];
    }

    /**
     * @return void
     */
    public function updateLocation()
    {
        $location = Yii::$app->sputnik->getLocation($this->fullname);

        if ($location) {
            self::updateAttributes(['lat' => $location['lat'], 'lon' => $location['lon']]);
        }
    }
}
