<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_street".
 *
 * @property int $id_street
 * @property string $aoguid
 * @property string $id_city
 * @property string $id_district
 * @property string $name
 * @property bool $is_updatable
 * @property bool $is_active
 * @property int $update_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property FiasAddrObj $addrObj
 * @property City $city
 * @property StreetDistrict[] $streetDistricts
 * @property District[] $districts
 * @property House[] $houses
 */
class Street extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Улица';
    const VERBOSE_NAME_PLURAL = 'Улицы';
    const TITLE_ATTRIBUTE = 'name';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_street';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aoguid'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['is_updatable', 'is_active'], 'boolean'],
            [['id_city'], 'default', 'value' => null],
            [['id_city'], 'integer'],
            [['id_city'], 'exist', 'targetClass' => City::class, 'targetAttribute' => 'id_city'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_street' => '#',
            'aoguid' => 'Aoguid',
            'id_city' => 'Город',
            'name' => 'Улица',
            'is_updatable' => 'Обновлять из ФИАС',
            'is_active' => 'Активный',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAddrObj()
    {
        return $this->hasOne(FiasAddrObj::class, ['aoguid' => 'aoguid']);
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
    public function getStreetDistricts()
    {
        return $this->hasMany(StreetDistrict::class, ['id_street' => 'id_street']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDistricts()
    {
        return $this->hasMany(District::class, ['id_district' => 'id_district'])
            ->via('streetDistricts');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_street' => 'id_street']);
    }
}
