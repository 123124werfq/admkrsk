<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hr_profile_positions".
 *
 * @property int $id_profile_position
 * @property int|null $id_profile
 * @property int|null $id_record_position
 * @property int|null $id_result
 * @property string|null $state
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class HrProfilePositions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_profile_positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_profile', 'id_record_position', 'id_result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_profile', 'id_record_position', 'id_result', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['state'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profile_position' => 'Id Profile Position',
            'id_profile' => 'Id Profile',
            'id_record_position' => 'Id Record Position',
            'id_result' => 'Id Result',
            'state' => 'State',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getPositionName()
    {
        $rec = CollectionRecord::findOne($this->id_record_position);
        if(!res)
            return false;
        $fields = $rec->getData(true);
        return $fields['name'];
    }
}
