<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_subregion".
 *
 * @property int $id_subregion
 * @property string $aoguid
 * @property int $id_region
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
 * @property House[] $houses
 */
class Subregion extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Район';
    const VERBOSE_NAME_PLURAL = 'Районы';
    const TITLE_ATTRIBUTE = 'name';

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
            [['is_updatable', 'is_active'], 'boolean'],
            [['id_region'], 'default', 'value' => null],
            [['id_region'], 'integer'],
            [['id_region'], 'exist', 'targetClass' => Region::class, 'targetAttribute' => 'id_region'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_subregion' => '#',
            'aoguid' => 'Aoguid',
            'id_region' => 'Регион',
            'name' => 'Район',
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
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_subregion' => 'id_subregion']);
    }
}
