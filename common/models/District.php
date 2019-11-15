<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_district".
 *
 * @property int $id_district
 * @property string $name
 *
 * @property House[] $houses
 */
class District extends \yii\db\ActiveRecord
{
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_district' => 'Id District',
            'name' => 'Район',
        ];
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
