<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_dashboard".
 *
 * @property int $id_dashboard
 * @property int|null $id_user
 * @property int|null $id_usergroup
 * @property string|null $name
 * @property string|null $link
 * @property string|null $icon
 * @property int|null $ord
 * @property int|null $update_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class Dashboard extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_dashboard';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_usergroup', 'ord', 'update_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'id_usergroup', 'ord', 'update_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name', 'link', 'icon'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_dashboard' => 'Id Dashboard',
            'id_user' => 'Id User',
            'id_usergroup' => 'Id Usergroup',
            'name' => 'Name',
            'link' => 'Link',
            'icon' => 'Icon',
            'ord' => 'Ord',
            'update_at' => 'Update At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
