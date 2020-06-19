<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "dbl_firm_user".
 *
 * @property int $id_record
 * @property int $id_user
 * @property int|null $state
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class FirmUser extends \yii\db\ActiveRecord
{
    const STATE_NEW = 0;
    const STATE_ACCEPT = 1;
    const STATE_DECLINE = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dbl_firm_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_record', 'id_user'], 'required'],
            [['id_record', 'id_user', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_record', 'id_user', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
        ];
    }


    public function getStateLabels()
    {
        return  [
            self::STATE_NEW=>'Новый',
            self::STATE_ACCEPT=>'Подтвержден',
            self::STATE_DECLINE=>'Отклонен',
        ];
    }


    public function getStateLabel()
    {
        $labels = $this->getStateLabels();

        return $labels[$this->state]??'';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_record' => 'Id Record',
            'id_user' => 'Id User',
            'state' => 'Статус',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }

    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }
}
