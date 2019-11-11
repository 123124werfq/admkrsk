<?php

namespace common\models;

use common\behaviors\OrderBehavior;
use common\modules\log\behaviors\LogBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%db_poll_answer}}".
 *
 * @property int $id_poll_answer
 * @property int $id_poll_question
 * @property string $answer
 * @property string $description
 * @property int $ord
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property int $votesCount
 * @property int $votesAvg
 * @property int $votesPercent
 * @property int $votesPercentRange
 *
 * @property Question $question
 * @property Vote[] $votes
 */
class Answer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%db_poll_answer}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer'], 'required'],
            [['id_poll_question', 'ord'], 'default', 'value' => null],
            [['id_poll_question', 'ord'], 'integer'],
            [['answer', 'description'], 'string'],
            [['id_poll_question'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['id_poll_question' => 'id_poll_question']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_poll_answer' => '#',
            'id_poll_question' => 'Вопрос',
            'answer' => 'Ответ',
            'description' => 'Описание',
            'ord' => 'Сортировка',
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
            'ba' => BlameableBehavior::class,
            'ord' => [
                'class' => OrderBehavior::class,
                'filters' => ['id_poll_question'],
            ],
            'log' => LogBehavior::class,
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuestion()
    {
        return $this->hasOne(Question::class, ['id_poll_question' => 'id_poll_question']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['id_poll_answer' => 'id_poll_answer']);
    }

    public function getVotesCount()
    {
        return $this->getVotes()
            ->select('MAX(id_poll_vote)')
            ->groupBy(['ip'])
            ->count();
    }

    public function getVotesAvg()
    {
        return $this->getVotes()
            ->select(['option' => new Expression('MAX(option)::integer')])
            ->groupBy(['ip'])
            ->average('option');
    }

    public function getVotesPercent()
    {
        $questionVotesCount = $this->question->votesCount;

        return $questionVotesCount > 0 ? number_format($this->votesCount / $questionVotesCount * 100, 1) : 0;
    }

    public function getVotesPercentRange()
    {
        $questionVotesCount = $this->question->answersCount;

        return $questionVotesCount > 0 ? number_format(($questionVotesCount - $this->votesAvg) / $questionVotesCount * 100, 1) : 0;
    }
}
