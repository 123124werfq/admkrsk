<?php

namespace common\models;

use Yii;

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
            [['id_user', 'begin', 'end', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
            [['title'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_contest' => 'Id Contest',
            'id_user' => 'Id User',
            'title' => 'Title',
            'begin' => 'Begin',
            'end' => 'End',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'deleted_by' => 'Deleted By',
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

}
