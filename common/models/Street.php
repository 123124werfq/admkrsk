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
    public function getHouses()
    {
        return $this->hasMany(House::class, ['id_street' => 'id_street']);
    }
}
