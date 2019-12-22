<?php

namespace common\models;

use Yii;

use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;


/**
 * This is the model class for table "hr_contest".
 *
 * @property int $id_contest
 * @property int $id_user
 * @property string $title
 * @property int $begin
 * @property int $end
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 */
class HrContest extends \yii\db\ActiveRecord
{
    use SoftDeleteTrait;

    const STATE_NOT_STARTED = 0;
    const STATE_STARTED = 1;
    const STATE_CLOSED = 2;
    const STATE_FINISHED = 99;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hr_contest';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'begin', 'end', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'begin', 'end', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_contest' => 'ID',
            'id_user' => 'Модератор',
            'title' => 'Название',
            'begin' => 'Дата начала',
            'end' => 'Дата завершения',
            'state' => 'Статус',
            'autostart' => 'Автоматическое начало голосования',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class
        ];
    }

    public function getPositions()
    {
        return $this->hasMany(HrProfilePositions::class, ['id_profile' => 'id_profile']);
    }

    public function getProfiles()
    {
        return $this->hasMany(HrProfile::class, ['id_profile' => 'id_profile'])->viaTable('hrl_contest_profile', ['id_contest' => 'id_contest']);
    }

    public function getExperts()
    {
        return $this->hasMany(HrExpert::class, ['id_expert' => 'id_expert'])->viaTable('hrl_contest_expert', ['id_contest' => 'id_contest']);
    }

    public function getResults()
    {
        return $this->hasMany(HrResult::class, ['id_contest' => 'id_contest']);
    }


    public function getStatename()
    {
        switch ($this->state){
            case HrContest::STATE_STARTED: return 'Текущее';
            case HrContest::STATE_CLOSED: return 'Подводятся итоги';
            case HrContest::STATE_FINISHED: return 'Итоги подведены';
        }

        return 'Не начато';
    }

}
