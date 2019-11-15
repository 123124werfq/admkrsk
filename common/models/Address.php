<?php

namespace common\models;

use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "map_address".
 *
 * @property int $id_address
 * @property string $fullname
 *
 */
class Address extends \yii\db\ActiveRecord
{
    use MetaTrait;

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
     * @return \yii\db\ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(House::class, ['id_house' => 'id_house']);
    }
}
