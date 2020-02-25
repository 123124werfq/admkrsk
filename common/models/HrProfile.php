<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;




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

    use SoftDeleteTrait;
    use MetaTrait;
    use ActionTrait;
    use AccessTrait;

    const STATE_ACTIVE = 0;
    const STATE_RESERVED = 1;
    const STATE_HIRED = 2;
    const STATE_BANNED = 3;
    const STATE_ARCHIVED = 99;

    const VERBOSE_NAME = 'Анкеты';
    const VERBOSE_NAME_PLURAL = 'Анкеты';
    const TITLE_ATTRIBUTE = 'id_profile';

    public $access_user_ids;
    public $access_user_group_ids;


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
            [['import_author', 'import_candidateid', 'import_timestamp'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profile' => 'ID профиля',
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
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.hrProfile',
            ]
        ];
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);

        $positions = $this->recordData['target_positions'];
        foreach ($this->positions as $pos)
        {
            if(empty($pos->id_result) && empty($positions[$pos->id_record_position]))
                $pos->delete();
            else
                unset($positions[$pos->id_record_position]);
        }

        if(is_array($positions))
            foreach($positions as $id_pos=>$label)
            {
                if(empty($id_pos))
                    continue;
                $posRecord = CollectionRecord::findOne($id_pos);
                if($posRecord){
                    $profilePosition = new HrProfilePositions;
                    $profilePosition->id_profile = $this->id_profile;
                    $profilePosition->id_record_position = $id_pos;
                    $profilePosition->state = (string)HrProfilePositions::STATE_OPEN;
                    $profilePosition->save();
                }
            }

        return true;
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function getReserved()
    {
        return $this->hasMany(HrReserve::class, ['id_profile' => 'id_profile']);
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

        if(!$record)
            return false;

        return $record->getData(true);
    }

    public function getName()
    {
        return $this->user->getUsername();
    }

    public function canUseInContest()
    {
        $result = false;

        if($this->state == HrProfile::STATE_ARCHIVED || $this->state == HrProfile::STATE_BANNED)
            return false;

        foreach ($this->positions as $pos){
            if($pos->state == 0 || is_null($pos->state))
                $result = true;
        }

        return $result;
    }

    public function getStatename($button = false)
    {
        if(!$button) {
            switch ($this->state) {
                case HrProfile::STATE_ACTIVE:
                    return 'Активно';
                case HrProfile::STATE_RESERVED:
                    return 'В кадровом резерве';
                case HrProfile::STATE_HIRED:
                    return 'Принят на должность';
                case HrProfile::STATE_BANNED:
                    return 'Заблокирован к участию';
                case HrProfile::STATE_ARCHIVED:
                    return 'В архиве';
            }
            return 'Активно';
        }
        else {
            switch ($this->state) {
                case HrProfile::STATE_ACTIVE:
                    return '<span class="badge badge-primary">Активно</span>';
                case HrProfile::STATE_RESERVED:
                    return '<span class="badge badge-warning">В кадровом резерве</span>';
                case HrProfile::STATE_HIRED:
                    return '<span class="badge badge-info">Принят на должность</span>';
                case HrProfile::STATE_BANNED:
                    return '<span class="badge badge-danger">Заблокирован к участию</span>';
                case HrProfile::STATE_ARCHIVED:
                    return '<span class="badge badge-secondary">В архиве</span>';
            }
            return '<span class="badge badge-primary">Активно</span>';

        }
    }

    public function isBusy()
    {
        foreach ($this->contests as $contest)
        {
            if($contest->state == HrContest::STATE_STARTED || $contest->state == HrContest::STATE_CLOSED)
                return true;
        }
        return false;
    }

}
