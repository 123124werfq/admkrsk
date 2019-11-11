<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%db_poll_vote}}".
 *
 * @property int $id_poll_vote
 * @property int $id_poll_question
 * @property int $id_poll_answer
 * @property string $option
 * @property string $ip
 * @property int $created_at
 * @property int $created_by
 *
 * @property Question $question
 * @property Answer $answer
 */
class Vote extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%db_poll_vote}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_poll_question'], 'required'],
            [['id_poll_question', 'id_poll_answer'], 'integer'],
            [['option'], 'string'],
            [['ip'], 'string', 'max' => 255],
            [['id_poll_question'], 'exist', 'skipOnError' => true, 'targetClass' => Question::class, 'targetAttribute' => ['id_poll_question' => 'id_poll_question']],
            [['id_poll_answer'], 'exist', 'skipOnError' => true, 'targetClass' => Answer::class, 'targetAttribute' => ['id_poll_answer' => 'id_poll_answer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_poll_vote' => '#',
            'id_poll_question' => 'Вопрос',
            'id_poll_answer' => 'Ответ',
            'option' => 'Свой вариант',
            'ip' => 'IP',
            'created_at' => 'Создано',
            'created_by' => 'Создал',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ],
            'ba' => [
                'class' => BlameableBehavior::class,
                'updatedByAttribute' => false,
            ],
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
    public function getAnswer()
    {
        return $this->hasOne(Answer::class, ['id_poll_answer' => 'id_poll_answer']);
    }
}
