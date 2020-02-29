<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_district".
 *
 * @property int $id_district
 * @property string $id_city
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
 * @property City $city
 * @property House[] $houses
 */
class District extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Район города';
    const VERBOSE_NAME_PLURAL = 'Районы города';
    const TITLE_ATTRIBUTE = 'name';

    const DISTRICT_ZHELEZNODOROZHNIY = '04401363000';
    const DISTRICT_KIROVSKIY = '04401365000';
    const DISTRICT_LENINSKIY = '04401368000';
    const DISTRICT_OKTYABRSKIY = '04401371000';
    const DISTRICT_SVERDLOVSKIY = '04401373000';
    const DISTRICT_SOVETSKIY = '04401374000';
    const DISTRICT_CENTRALNIY = '04401377000';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_district';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'id_district' => '#',
            'id_city' => 'Город',
            'name' => 'Район',
            'is_updatable' => 'Обновлять из ФИАС',
            'is_active' => 'Активный',
        ];
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
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_district' => 'id_district']);
    }

    /**
     * @return array
     */
    public static function getDistrictNames()
    {
        return [
            self::DISTRICT_ZHELEZNODOROZHNIY => 'Железнодорожный район',
            self::DISTRICT_KIROVSKIY => 'Кировский район',
            self::DISTRICT_LENINSKIY => 'Ленинский район',
            self::DISTRICT_OKTYABRSKIY => 'Октябрьский район',
            self::DISTRICT_SVERDLOVSKIY => 'Свердловский район',
            self::DISTRICT_SOVETSKIY => 'Советский район',
            self::DISTRICT_CENTRALNIY => 'Центральный район',
        ];
    }

    /**
     * @return string|null
     */
    public function getDistrictName()
    {
        $names = District::getDistrictNames();

        if ($names[$this->id_district]) {
            return $names[$this->id_district];
        }

        return null;
    }

    /**
     * @param string $okato
     * @return string|null
     */
    public static function getDistrictNameByOKATO($okato)
    {
        $names = District::getDistrictNames();

        if (isset($names[$okato])) {
            return $names[$okato];
        }

        return null;
    }
}
