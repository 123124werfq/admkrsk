<?php

namespace common\models;

use common\traits\AccessTrait;
use common\traits\MetaTrait;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "map_address".
 *
 * @property int $id_address
 * @property string $fullname
 *
 */
class Address extends ActiveRecord
{
    use MetaTrait;
    use AccessTrait;

    const VERBOSE_NAME = 'Адрес';
    const VERBOSE_NAME_PLURAL = 'Адреса';
    const TITLE_ATTRIBUTE = 'fullname';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'map_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fullname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fullname' => 'Адрес',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(House::class, ['id_house' => 'id_house']);
    }
}
