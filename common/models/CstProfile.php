<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;


/**
 * This is the model class for table "cst_profile".
 *
 * @property int $id_profile
 * @property int|null $id_user
 * @property int|null $id_record_anketa
 * @property int|null $id_record_contest
 * @property int|null $state
 * @property int|null $created_at
 * @property int|null $created_by
 * @property int|null $updated_at
 * @property int|null $updated_by
 * @property int|null $deleted_at
 * @property int|null $deleted_by
 */
class CstProfile extends \yii\db\ActiveRecord
{

    use MetaTrait;
    use ActionTrait;
    use SoftDeleteTrait;
    use AccessTrait;

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
        return 'cst_profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_user', 'id_record_anketa', 'id_record_contest', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'default', 'value' => null],
            [['id_user', 'id_record_anketa', 'id_record_contest', 'state', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'], 'integer'],
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
            'id_record_anketa' => 'Id Record Anketa',
            'id_record_contest' => 'Id Record Contest',
            'state' => 'State',
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
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.contests',
            ],
        ];
    }


    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_anketa']);
    }

    public function getContest()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_contest']);
    }      
}
