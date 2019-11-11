<?php

namespace common\models;

use common\behaviors\OrderBehavior;
use common\components\yiinput\RelationBehavior;
use common\modules\log\behaviors\LogBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%db_poll_question}}".
 *
 * @property int $id_poll_question
 * @property int $id_poll
 * @property int $type
 * @property string $question
 * @property string $description
 * @property int $ord
 * @property bool $is_option
 * @property bool $is_hidden
 * @property int $chart_type
 * @property int $created_at
 * @property int $created_by
 * @property int $updated_at
 * @property int $updated_by
 * @property int $deleted_at
 * @property int $deleted_by
 * @property string $breadcrumbsLabel
 * @property string $pageTitle
 * @property string $typeName
 * @property string $chartTypeName
 * @property string $dataValues
 * @property string $chartLabels
 * @property string $chartMainLabels
 * @property array $results
 * @property string $mainResults
 * @property int $answersCount
 * @property int $votesCount
 * @property int $votesAvg
 * @property int $freeVotesCount
 *
 * @property Answer[] $answers
 * @property Poll $poll
 * @property User $createdBy
 * @property User $updatedBy
 * @property User $deletedBy
 */
class Question extends \yii\db\ActiveRecord
{
    const TYPE_ONLY = 1;
    const TYPE_MULTIPLE = 2;
    const TYPE_FREE_FORM = 3;
    const TYPE_RANGING = 4;

    const CHART_TYPE_BAR_V = 1;
    const CHART_TYPE_BAR_H = 2;
    const CHART_TYPE_PIE = 3;
    const CHART_TYPE_GRAPH = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%db_poll_question}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_poll', 'question'], 'required'],
            [['type'], 'default', 'value' => self::TYPE_ONLY],
            [['chart_type'], 'default', 'value' => self::CHART_TYPE_BAR_V],
            [['id_poll', 'type', 'ord', 'chart_type'], 'integer'],
            [['question', 'description'], 'string'],
            [['is_option', 'is_hidden'], 'boolean'],
            [['id_poll'], 'exist', 'skipOnError' => true, 'targetClass' => Poll::class, 'targetAttribute' => ['id_poll' => 'id_poll']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_poll_question' => '#',
            'id_poll' => 'Опрос',
            'type' => 'Тип',
            'question' => 'Вопрос',
            'description' => 'Описание',
            'ord' => 'Сортировка',
            'is_option' => 'Свой вариант',
            'is_hidden' => 'Скрыть результаты',
            'chart_type' => 'Тип диаграммы',
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
                'filters' => ['id_poll'],
            ],
            'log' => LogBehavior::class,
            'yiinput' => [
                'class' => RelationBehavior::class,
                'relations' => [
                    'answers' => [
                        'modelname' => 'Answer',
                        'added' => true,
                    ],
                ]
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAnswers()
    {
        return $this->hasMany(Answer::class, ['id_poll_question' => 'id_poll_question'])
            ->orderBy('ord');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPoll()
    {
        return $this->hasOne(Poll::class, ['id_poll' => 'id_poll']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Vote::class, ['id_poll_answer' => 'id_poll_answer'])
            ->via('answers');
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
     * @return string
     */
    public function getBreadcrumbsLabel()
    {
        return 'Опросы';
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->question;
    }

    /**
     * Возвращает массив типов
     *
     * @return array
     */
    public static function getTypeNames()
    {
        return [
            self::TYPE_ONLY => 'Единственный выбор',
            self::TYPE_MULTIPLE => 'Множественный выбор',
            self::TYPE_FREE_FORM => 'В свободной форме',
            self::TYPE_RANGING => 'Ранжирование',
        ];
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getTypeName()
    {
        $types = self::getTypeNames();

        if ($types[$this->type]) {
            return $types[$this->type];
        }

        return null;
    }

    /**
     * Возвращает массив типов
     *
     * @return array
     */
    public static function getChartTypeNames()
    {
        return [
            self::CHART_TYPE_BAR_V => 'Вертикальный',
            self::CHART_TYPE_BAR_H => 'Горизонтальный',
            self::CHART_TYPE_PIE => 'Пирог',
//            self::CHART_TYPE_GRAPH => 'График',
        ];
    }

    /**
     * Возвращает название типа
     *
     * @return string
     */
    public function getChartTypeName()
    {
        $statuses = self::getChartTypeNames();

        if ($statuses[$this->chart_type]) {
            return $statuses[$this->chart_type];
        }

        return null;
    }

    public function getResults()
    {
        $results = [];

        foreach ($this->answers as $answer_index => $answer) {
            $percent = $this->type == self::TYPE_RANGING ? $answer->votesPercentRange : $answer->votesPercent;
            $count = $answer->votesCount;

            $results[$answer->id_poll_answer] = [
                'id_poll_answer' => $answer->id_poll_answer,
                'percent' => $percent,
            ];

            if ($this->chart_type == self::CHART_TYPE_GRAPH) {
                $results[$answer->id_poll_answer]['label'] = '["Ответ ' . ($answer_index + 1) . '"]';
            } elseif ($this->chart_type == self::CHART_TYPE_PIE) {
                $results[$answer->id_poll_answer]['label'] = '["Ответ ' . ($answer_index + 1) . ' - ' . $percent . '%, ' . $count. ' чел."]';
            } else {
                $results[$answer->id_poll_answer]['label'] = '["Ответ ' . ($answer_index + 1) . '", "' . $count. ' чел."]';
            }
        }

        ArrayHelper::multisort($results, 'percent', SORT_DESC, SORT_NUMERIC);

        return $results;
    }

    public function getMainResults()
    {
        $results = [];

        foreach ($this->answers as $answer_index => $answer) {
            $percent = $this->type == self::TYPE_RANGING ? $answer->votesPercentRange : $answer->votesPercent;
            $count = $answer->votesCount;

            $results[$answer->id_poll_answer] = [
                'id_poll_answer' => $answer->id_poll_answer,
                'percent' => $percent,
            ];

            $results[$answer->id_poll_answer]['label'] = '["' . implode('", "', explode(' ', $answer->answer)) . '"]';
        }

        ArrayHelper::multisort($results, 'percent', SORT_DESC, SORT_NUMERIC);

        return $results;
    }

    public function getDataValues()
    {
        $results = $this->results;

//        if ($this->type == self::TYPE_RANGING) {
//            $sum = 0;
//            foreach ($results as $result) {
//                $sum += $result['percent'];
//            }
//
//            foreach ($results as $key => $result) {
//                $results[$key]['percent'] = floor(($result['percent'] / $sum) * 100);
//            }
//        }

        return implode(',', ArrayHelper::getColumn($results, 'percent'));
    }

    public function getChartLabels()
    {
        return implode(',', ArrayHelper::getColumn($this->results, 'label'));
    }

    public function getChartMainLabels()
    {
        return implode(',', ArrayHelper::getColumn($this->mainResults, 'label'));
    }

    public function getAnswersCount()
    {
        return $this->getAnswers()
            ->count();
    }

    public function getVotesCount()
    {
        return $this->getVotes()
            ->select('MAX(id_poll_vote)')
            ->groupBy(['id_poll_answer', 'ip'])
            ->count();
    }

    public function getVotesAvg()
    {
        return $this->getVotes()
            ->select(['option' => new Expression('MAX(option)::integer')])
            ->groupBy(['id_poll_answer', 'ip'])
            ->average('option');
    }

    public function getFreeVotesCount()
    {
        return Vote::find()
            ->select('MAX(id_poll_vote)')
            ->leftJoin(self::tableName(), self::tableName() . '.id_poll_question = ' . Vote::tableName() . '.id_poll_question')
            ->groupBy(['id_poll_answer', 'ip'])
            ->count();
    }
}
