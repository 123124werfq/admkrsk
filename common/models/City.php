<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_city".
 *
 * @property int $id_city
 * @property string $aoguid
 * @property string $id_region
 * @property string $id_subregion
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
 * @property Region $region
 * @property Subregion $subregion
 * @property House[] $houses
 */
class City extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Город';
    const VERBOSE_NAME_PLURAL = 'Города';
    const TITLE_ATTRIBUTE = 'name';

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
            [['is_updatable', 'is_active'], 'boolean'],
            [['id_region', 'id_subregion'], 'default', 'value' => null],
            [['id_region', 'id_subregion'], 'integer'],
            [['id_region'], 'exist', 'targetClass' => Region::class, 'targetAttribute' => 'id_region'],
            [['id_subregion'], 'exist', 'targetClass' => Subregion::class, 'targetAttribute' => 'id_subregion'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_city' => '#',
            'aoguid' => 'Aoguid',
            'id_region' => 'Регион',
            'id_subregion' => 'Район',
            'name' => 'Город',
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
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_city' => 'id_city']);
    }
}
