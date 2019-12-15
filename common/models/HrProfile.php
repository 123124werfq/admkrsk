<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\behaviors\AccessControlBehavior;



/**
 * This is the model class for table "hr_profile".
 *
 * @property int $id_profile
 * @property int $id_user
 * @property int $id_record
 * @property string $state
 * @property int $reserve_date
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class HrProfile extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_record', 'reserve_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'id_record', 'reserve_date', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['state'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profile' => 'Id Profile',
            'id_user' => 'Id User',
            'id_record' => 'Id Record',
            'state' => 'State',
            'reserve_date' => 'Reserve Date',
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
            /*
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.news',
            ]
            */
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $positions = $this->recordData['target_positions'];

        foreach ($this->positions as $pos)
        {
            if(empty($pos->id_result) && !in_array($pos->id_position, $positions))
                $pos->delete();
            else
                $positions = array_diff( $positions, [$pos->id_position] );
        }

        foreach($positions as $id_pos)
        {
            $posRecord = CollectionRecord::findOne($id_pos);
            if($posRecord){
                $profilePosition = new HrProfilePositions;
                $profilePosition->id_profile = $this->id_profile;
                $profilePosition->id_record_position = $id_pos;
                $profilePosition->save();
            }
        }

        return true;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getPositions()
    {
        return $this->hasMany(HrProfilePositions::class, ['id_profile' => 'id_profile']);
    }

    public function getContests()
    {
        return $this->hasMany(HrContest::class, ['id_contest' => 'id_contest'])->viaTable('hrl_contest_profile', ['id_profile' => 'id_profile']);
    }

    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record']);
    }

    public function getRecordData()
    {
        if(!$this->id_record)
            return false;

        $record = CollectionRecord::findOne($this->id_record);

        return $record->getData(true);
    }

    public function getName()
    {
        return $this->user->getUsername();
    }

}
