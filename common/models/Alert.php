<?php

namespace common\models;

use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;

/**
 * This is the model class for table "db_alert".
 *
 * @property int $id_alert
 * @property int $id_page
 * @property string $content
 * @property int $date_begin
 * @property int $date_end
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class Alert extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Всплывающее сообщение';
    const VERBOSE_NAME_PLURAL = 'Всплывающие сообщения';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_alert';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_page', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_page', 'date_begin', 'date_end', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by','state'], 'integer'],
            [['content','date_begin', 'date_end'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_alert' => 'ID',
            'id_page' => 'Раздел',
            'content' => 'Содержание',
            'date_begin' => 'Дата начала показа',
            'date_end' => 'Дата конца показа',
            'state' => 'Активно',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getPage()
    {
        return $this->hasOne(Page::class, ['id_page' => 'id_page']);
    }
}
