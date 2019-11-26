<?php

namespace common\models;

use common\behaviors\DatetimeBehavior;
use common\behaviors\AccessControlBehavior;
use common\modules\log\behaviors\LogBehavior;
use common\traits\ActionTrait;
use common\traits\MetaTrait;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%db_poll}}".
 *
 * @property int $id_poll
 * @property int $status
 * @property string $title
 * @property string $description
 * @property string $result
 * @property bool $is_anonymous
 * @property bool $is_hidden
 * @property int $date_start
 * @property int $date_end
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $statusName
 * @property array $access_user_ids
 *
 * @property Question[] $questions
 * @property Vote[] $votes
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $deletedBy
 */
class Poll extends \yii\db\ActiveRecord
{
    use MetaTrait;
    use ActionTrait;

    const VERBOSE_NAME = 'Опрос';
    const VERBOSE_NAME_PLURAL = 'Опросы';
    const TITLE_ATTRIBUTE = 'title';

    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 2;

    public $access_user_ids;
    public $access_user_group_ids;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%db_poll}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['date_start', 'date_end'], 'default', 'value' => null],
            [['date_start', 'date_end'], 'date', 'format' => 'dd.MM.yyyy HH:mm'],
            [['status'], 'integer'],
            [['description', 'result'], 'string'],
            [['is_anonymous', 'is_hidden'], 'boolean'],
            [['title'], 'string', 'max' => 255],
            [['status'], 'in', 'range' => [self::STATUS_INACTIVE, self::STATUS_ACTIVE]],

            [['access_user_ids', 'access_user_group_ids'], 'each', 'rule' => ['integer']],
            ['access_user_ids', 'each', 'rule' => ['exist', 'targetClass' => User::class, 'targetAttribute' => 'id']],
            ['access_user_group_ids', 'each', 'rule' => ['exist', 'targetClass' => UserGroup::class, 'targetAttribute' => 'id_user_group']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_poll' => '#',
            'status' => 'Статус',
            'title' => 'Название',
            'description' => 'Описание',
            'result' => 'Описание результатов',
            'is_anonymous' => 'Анонимный',
            'is_hidden' => 'Скрыть результаты',
            'date_start' => 'Начинается',
            'date_end' => 'Заканчивается',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
            'updated_at' => 'Обновлено',
            'updated_by' => 'Обновил',
            'deleted_at' => 'Удалено',
            'deleted_by' => 'Удалил',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => TimestampBehavior::class,
            'dt' => [
                'class' => DatetimeBehavior::class,
                'attributes' => ['date_start', 'date_end'],
                'format' => 'dd.MM.yyyy HH:mm:ss',
            ],
            'ba' => BlameableBehavior::class,
            'log' => LogBehavior::class,
            'ac' => [
                'class' => AccessControlBehavior::class,
                'permission' => 'backend.poll',
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestions()
    {
        return $this->hasMany(Question::class, ['id_poll' => 'id_poll'])
            ->orderBy('ord');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['id_poll_question' => 'id_poll_question'])
            ->via('questions');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDeletedBy()
    {
        return $this->hasOne(User::class, ['id' => 'deleted_by']);
    }

    /**
     * Возвращает массив статусов
     *
     * @return array
     */
    public static function getStatusNames()
    {
        return [
            self::STATUS_INACTIVE => 'Не активный',
            self::STATUS_ACTIVE => 'Активный',
         ];
    }

    /**
     * Возвращает название статуса
     *
     * @return string
     */
    public function getStatusName()
    {
        $statuses = self::getStatusNames();

        if ($statuses[$this->status]) {
            return $statuses[$this->status];
        }

        return null;
    }

    public function isPassed()
    {
        return $this->getVotes()
            ->where(['ip' => Yii::$app->request->getUserIP()])
            ->exists();
    }

    public function isExpired()
    {
        return !($this->status == self::STATUS_ACTIVE && $this->date_start < time() && $this->date_end > time());
    }

    public static function activeCount()
    {
        return Poll::find()
            ->where([
                'and',
                ['<', 'date_start', time()],
                ['>', 'date_end', time()],
            ])
            ->count();
    }

    public static function voitesCount()
    {
        return Vote::find()
            ->select(['id_poll_vote' => 'MAX(id_poll_vote)'])
            ->leftJoin(Question::tableName(), Question::tableName() . '.id_poll_question = ' . Vote::tableName() . '.id_poll_question')
            ->leftJoin(Poll::tableName(), Poll::tableName() . '.id_poll = ' . Question::tableName() . '.id_poll')
            ->groupBy([Poll::tableName() . '.id_poll', 'ip'])
            ->count('id_poll_vote');
    }

    public static function getIdRandomActivePool()
    {
        if (($poll = self::find()->andWhere(['<', 'date_end', time()])->orderBy('random()')->one()) === null) {
            return false;
        }

        return $poll->id_poll;
    }
}
