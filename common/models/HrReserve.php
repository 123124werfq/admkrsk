<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;

/**
 * This is the model class for table "hr_reserve".
 *
 * @property int $id_reserve
 * @property int|null $id_profile
 * @property int|null $id_record_position
 * @property int|null $id_result
 * @property int|null $contest_date
 * @property int|null $state
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class HrReserve extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_reserve';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_profile', 'id_record_position', 'id_result', 'contest_date', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_profile', 'id_record_position', 'id_result', 'contest_date', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_reserve' => 'Id Reserve',
            'id_profile' => 'Id Profile',
            'id_record_position' => 'Id Record Position',
            'id_result' => 'Id Result',
            'contest_date' => 'Contest Date',
            'state' => 'State',
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
            'log' => LogBehavior::class
            /*
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.news',
            ]
            */
        ];
    }

    public function getProfile()
    {
        return $this->hasOne(HrProfile::class, ['id_profile' => 'id_profile']);
    }


    public function getPositionName()
    {
        $res = CollectionRecord::findOne($this->id_record_position);
        if(!$res)
            return false;
        $fields = $res->getData(true);

        return array_shift($fields);
    }

}
