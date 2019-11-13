<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_city".
 *
 * @property int $id_city
 * @property string $aoguid
 * @property string $name
 *
 * @property FiasAddrObj $addrObj
 * @property House[] $houses
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['aoguid'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_city' => 'Id City',
            'aoguid' => 'Aoguid',
            'name' => 'Город',
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
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_city' => 'id_city']);
    }
}
