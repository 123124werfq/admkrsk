<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "auth_esia_firm".
 *
 * @property int $id_esia_firm
 * @property int|null $id_user
 * @property int|null $active
 * @property string|null $oid
 * @property string|null $shortname
 * @property string|null $fullname
 * @property string|null $type
 * @property string|null $ogrn
 * @property string|null $inn
 * @property string|null $leg
 * @property string|null $kpp
 * @property string|null $ctts
 * @property string|null $email
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class EsiaFirm extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_esia_firm';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'active', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'active', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['oid', 'shortname', 'fullname', 'type', 'ogrn', 'inn', 'leg', 'kpp', 'ctts', 'email', 'main_addr', 'main_addr_fias', 'main_addr_fias_alt', 'law_addr', 'law_addr_fias', 'law_addr_fias_alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_esia_firm' => 'Id Esia Firm',
            'id_user' => 'Id User',
            'active' => 'Active',
            'oid' => 'Oid',
            'shortname' => 'Shortname',
            'fullname' => 'Fullname',
            'type' => 'Type',
            'ogrn' => 'Ogrn',
            'inn' => 'Inn',
            'leg' => 'Leg',
            'kpp' => 'Kpp',
            'ctts' => 'Ctts',
            'email' => 'Email',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
