<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_controller_page".
 *
 * @property int $id
 * @property int $id_page
 * @property string $controller
 * @property string $actions
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class ControllerPage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_controller_page';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page','controller'],'required'],
            [['id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['actions'], 'string'],
            [['controller'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_page' => 'Раздел',
            'controller' => 'Controller',
            'actions' => 'Actions',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            $this->actions = explode(',', $this->actions);

            return true;
        }
        else
            return false;
    }*/

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }
}
