<?php

namespace common\models;

use common\components\softdelete\SoftDeleteTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "{{%map_place}}".
 *
 * @property int $id_place
 * @property int|null $id_house
 * @property string|null $name
 * @property float|null $lat
 * @property float|null $lon
 * @property int $update_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 *
 * @property House $house
 */
class Place extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;

    const VERBOSE_NAME = 'Место';
    const VERBOSE_NAME_PLURAL = 'Места';
    const TITLE_ATTRIBUTE = 'name';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%map_place}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_house'], 'default', 'value' => null],
            [['id_house'], 'integer'],
            [['lat', 'lon'], 'number'],
            [['name'], 'string', 'max' => 255],
            [['id_place'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['id_place' => 'id_house']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_place' => '#',
            'id_house' => 'Дом',
            'name' => 'Название',
            'lat' => 'Широта',
            'lon' => 'Долгота',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(House::class, ['id_house' => 'id_house']);
    }
}
