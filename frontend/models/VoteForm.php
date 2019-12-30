<?php

namespace frontend\models;

use common\models\Answer;
use common\models\Question;
use Yii;
use yii\base\Model;

/**
 * @property Answer[] $answers
 */
class VoteForm extends Model
{
    /**
     * @var Question
     */
    public $question;

    public $answer_ids = [];
    public $option;

    public function __construct(Question $question, $config = [])
    {
        $this->question = $question;

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['question', 'validateQuestion'],
            ['answer_ids', 'each', 'rule' => ['integer']],
            [
                'answer_ids', 'each', 'rule' => [
                    'exist',
                    'targetClass' => Answer::class,
                    'targetAttribute' => 'id_poll_answer',
                    'filter' => ['id_poll_question' => $this->question->id_poll_question],
                    'message' => 'Неверный ответ'
                ],
            ],
            ['option', 'string', 'max' => 280],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validateQuestion($attribute): void
    {
        if (in_array($this->question->type, [Question::TYPE_ONLY, Question::TYPE_MULTIPLE])) {
            if ($this->question->is_option) {
                if (empty($this->answer_ids) && empty($this->option)) {
                    $this->addError('id_poll_question', 'Выберите или введите свой ответ');
                }
            } else {
                if (empty($this->answer_ids)) {
                    $this->addError('id_poll_question', 'Выберите ответ');
                }
            }
        } elseif (in_array($this->question->type, [Question::TYPE_FREE_FORM])) {
            if (empty($this->option)) {
                $this->addError('id_poll_question', 'Введите ответ');
            }
        }
    }

    /**
     * @return Answer[]
     */
    public function getAnswers()
    {
        if (empty($this->answer_ids)) {
            return $this->question->answers;
        }

        $answers = [];
        foreach ($this->answer_ids as $answer_id) {
            foreach ($this->question->answers as $answer) {
                if ($answer->id_poll_answer == $answer_id) {
                    $answers[] = $answer;
                }
            }
        }

        return $answers;
    }
}
