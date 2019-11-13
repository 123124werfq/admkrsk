<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_street".
 *
 * @property int $id_street
 * @property string $aoguid
 * @property string $name
 *
 * @property FiasAddrObj $addrObj
 * @property House[] $houses
 */
class Street extends \yii\db\ActiveRecord
{
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_street' => 'Id Street',
            'aoguid' => 'Aoguid',
            'name' => 'Улица',
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
        return $this->hasMany(House::class, ['id_street' => 'id_street']);
    }
}
