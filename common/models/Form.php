<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "form_form".
 *
 * @property int $id_form
 * @property int $id_collection
 * @property string $name
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Form extends \yii\db\ActiveRecord
{
    public $make_collection=false;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'form_form';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_collection', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','make_collection'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_form' => 'ID',
            'id_collection' => 'Коллекция',
            'name' => 'Название',
            'make_collection'=>'Создать коллекцию',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getCollection()
    {
        return $this->hasMany(Collection::class, ['id_collection' => 'id_collection']);
    }

    public function getRows()
    {
        return $this->hasMany(FormRow::class, ['id_form' => 'id_form']);
    }

    public function getService()
    {
        return $this->hasMany(Service::class, ['id_form' => 'id_form']);
    }
}
