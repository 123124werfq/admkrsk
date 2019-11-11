<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_department".
 *
 * @property int $id_department
 * @property int $id_parent
 * @property int $id_boss
 * @property string $fullname
 * @property string $shortname
 * @property string $address
 * @property string $email
 * @property string $phone
 * @property string $fax
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_department';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'id_boss', 'ord', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_parent', 'id_boss', 'ord', 'created_at', 'created_by', 'updated_at', 'deleted_at', 'deleted_by'], 'integer'],
            [['fullname', 'shortname', 'address', 'email', 'phone', 'fax'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_department' => 'Id Department',
            'id_parent' => 'Id Parent',
            'id_boss' => 'Id Boss',
            'fullname' => 'Fullname',
            'shortname' => 'Shortname',
            'address' => 'Address',
            'email' => 'Email',
            'phone' => 'Phone',
            'fax' => 'Fax',
            'ord' => 'Ord',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
