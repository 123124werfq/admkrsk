<?php

namespace common\models;
use common\traits\ActionTrait;

use common\components\softdelete\SoftDeleteTrait;

use Yii;

/**
 * This is the model class for table "db_tag".
 *
 * @property int $id
 * @property int $frequency
 * @property string $name
 */
class Tag extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;
    use ActionTrait;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'db_tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['frequency'], 'default', 'value' => null],
            [['frequency'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'frequency' => 'Частота использования',
            'name' => 'Название',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }
}
