<?php

namespace common\models;

use common\behaviors\AccessControlBehavior;
use common\components\softdelete\SoftDeleteTrait;
use common\modules\log\behaviors\LogBehavior;
use common\traits\AccessTrait;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
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

    const STATE_DRAFT = 0;
    const STATE_ACCEPTED = 1;
    const STATE_REJECTED = 100;
    const STATE_ARCHIVED = 99;    

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
            [['comment'], 'string'],
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
            'comment' => 'Комментарий',
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
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.cstProfile',
            ],
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }    

    public function getRecord()
    {
        return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_anketa']);
    }

    public function getContestinfo()
    {
        //return $this->hasOne(CollectionRecord::class, ['id_record' => 'id_record_contest']);

        $contestCollection = Collection::find()->where(['alias'=>'contests_list'])->one();
        if(!$contestCollection)
            return false;

        $contests = $contestCollection->getDataQuery()->getArray(true);

        foreach ($contests as $ackey => $contest) {
            if(!empty($contest['participant_form']))
            {
                $form = Form::find()->where(['alias' => $contest['participant_form']])->one();
                if(!$form)
                    continue;

                if($form->id_collection == $this->id_record_contest)
                    return $contest;
            }
        }
        return false;

    }      

    public function getStatename($button = false)
    {
        if(!$button) {
            switch ($this->state) {
                case CstProfile::STATE_DRAFT:
                    return 'Черновик';
                case CstProfile::STATE_ACCEPTED:
                    return 'Принято к рассмотрению';
                case CstProfile::STATE_REJECTED:
                    return 'Отклонено';
                case CstProfile::STATE_ARCHIVED:
                    return 'В архиве';
            }
            return 'Активно';
        }
        else {
            switch ($this->state) {
                case CstProfile::STATE_DRAFT:
                    return '<span class="badge badge-primary">Черновик</span>';
                case CstProfile::STATE_ACCEPTED:
                    return '<span class="badge badge-warning">Принято к рассмотрению</span>';
                case CstProfile::STATE_REJECTED:
                    return '<span class="badge badge-danger">Отклонено</span>';
                case CstProfile::STATE_ARCHIVED:
                    return '<span class="badge badge-secondary">В архиве</span>';
            }
            return '<span class="badge badge-primary">Активно</span>';

        }
    }
    

}
