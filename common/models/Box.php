<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "db_box".
 *
 * @property int $id_box
 * @property string $name
 */
class Box extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_box';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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
            'id_box' => '#',
            'name' => 'Название',
        ];
    }
}
