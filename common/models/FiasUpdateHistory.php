<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "fias_update_history".
 *
 * @property int $id
 * @property int $version
 * @property string $text
 * @property string $file
 * @property int $created_at
 * @property int $updated_at
 */
class FiasUpdateHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fias_update_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['version'], 'integer'],
            [['text', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'version' => 'Идентификатор',
            'text' => 'Описание',
            'file' => 'Файл',
//            'all_count' => 'Всего адресов',
//            'manual_count' => 'Свои адреса',
//            'insert_count' => 'Добавлено адресов',
//            'update_count' => 'Обновлено адресов',
            'created_at' => 'Создано',
            'updated_at' => 'Обновлено',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
        ];
    }
}
