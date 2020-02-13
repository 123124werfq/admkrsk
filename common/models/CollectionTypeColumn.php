<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "collection_type_column".
 *
 * @property int $id_column
 * @property int $id_type
 * @property string $name
 * @property int $type
 * @property string|null $settings
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class CollectionTypeColumn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'collection_type_column';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_type', 'name', 'type', 'alias'], 'required'],
            [['id_type', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_type', 'type', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['settings','alias'], 'string'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_column' => 'ID',
            'id_type' => 'Id Type',
            'name' => 'Название',
            'type' => 'Тип колонки',
            'alias' => 'Псевдоним',
            'settings' => 'Settings',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
