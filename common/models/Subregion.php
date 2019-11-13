<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "map_subregion".
 *
 * @property int $id_subregion
 * @property string $aoguid
 * @property string $name
 *
 * @property FiasAddrObj $addrObj
 * @property House[] $houses
 */
class Subregion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_subregion';
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
            'id_subregion' => 'Id Subregion',
            'aoguid' => 'Aoguid',
            'name' => 'Район',
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
        return $this->hasMany(House::class, ['id_subregion' => 'id_subregion']);
    }
}
