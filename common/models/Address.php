<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_address".
 *
 * @property int $id
 * @property string $houseguid
 * @property string $address
 * @property string $breadcrumbsLabel
 * @property string $pageTitle
 *
 * @property House $house
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_address';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['houseguid'], 'string'],
            [['address'], 'string', 'max' => 255],
            [['houseguid'], 'exist', 'skipOnError' => true, 'targetClass' => House::class, 'targetAttribute' => ['houseguid' => 'houseguid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'houseguid' => 'ФИАС GUID',
            'address' => 'Адрес',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHouse()
    {
        return $this->hasOne(House::class, ['houseguid' => 'houseguid']);
    }

    /**
     * @return string
     */
    public function getBreadcrumbsLabel()
    {
        return 'Адреса';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->addressName;
    }
}
