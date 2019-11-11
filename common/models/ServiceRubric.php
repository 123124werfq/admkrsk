<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service_rubric".
 *
 * @property int $id_rub
 * @property int $id_parent
 * @property string $name
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ServiceRubric extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service_rubric';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'],'required'],
            [['id_parent', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_parent', 'ord', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
          'id_rub' => 'ID',
          'id_parent' => 'Родительский элемент',
          'name' => 'Название',
          'ord' => 'Ord',
          'created_at' => 'Created At',
          'created_by' => 'Created By',
          'updated_at' => 'Updated At',
          'updated_by' => 'Updated By',
          'deleted_at' => 'Deleted At',
          'deleted_by' => 'Deleted By',
        ];
    }

    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id_rub' => 'id_rub']);
    }

    public function getChilds()
    {
        return $this->hasMany(ServiceRubric::className(), ['id_parent' => 'id_rub']);
    }

    public function getParent()
    {
        return $this->hasMany(ServiceRubric::className(), ['id_rub' => 'id_parent']);
    }
}
