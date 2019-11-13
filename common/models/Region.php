<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_region".
 *
 * @property int $id_region
 * @property string $aoguid
 * @property string $name
 *
 * @property FiasAddrObj $addrObj
 * @property House[] $houses
 */
class Region extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_region';
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
            'id_region' => 'Id Region',
            'aoguid' => 'Aoguid',
            'name' => 'Регион',
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
        return $this->hasMany(House::class, ['id_region' => 'id_region']);
    }
}
