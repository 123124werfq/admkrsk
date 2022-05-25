<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "appeal_request".
 *
 * @property int $id_request
 * @property int $id_record
 * @property int $is_anonimus
 * @property int $id_user
 * @property string $comment
 * @property string $number_internal
 * @property string $number_system
 * @property string $number_common
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class AppealRequest extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'appeal_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_record', 'is_anonimus', 'id_user', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_record', 'is_anonimus', 'id_user', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['comment', 'number_internal', 'number_system', 'number_common', 'data'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_request' => 'Id Request',
            'id_record' => 'Id Record',
            'is_anonimus' => 'Is Anonimus',
            'id_user' => 'Id User',
            'comment' => 'Comment',
            'number_internal' => 'Number Internal',
            'number_system' => 'Number System',
            'number_common' => 'Number Common',
            'data' => '',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
        ];
    }

    public function getRecordData()
    {
        if(!$this->id_record)
            return false;

        $record = CollectionRecord::findOne($this->id_record);

        return $record->getData(true);
    }


    public function getStatusName()
    {
        $as = AppealState::find()->where(['id_request' => $this->id_record])->orderBy('id_state DESC')->one();

        switch ($as)
        {
            case 0: return 'ОЖИДАЕТ РЕГИСТРАЦИИ';
            case 1: return 'ЗАРЕГИСТРИРОВАНО';
        }
    }
}
