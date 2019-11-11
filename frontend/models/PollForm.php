<?php

namespace frontend\models;

use common\models\Vote;
use Yii;
use yii\base\Model;

class PollForm extends Model
{
    public $id_poll;
    public $questions;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['questions', 'safe'],
        ];
    }

    public function save()
    {
        foreach ($this->questions as $id_poll_question => $question) {
            if (isset($question['answers']) && !empty($question['answers'])) {
                foreach ($question['answers'] as $id_answer => $answer) {
                    $vote = new Vote();
                    $vote->id_poll_question = $id_poll_question;
                    $vote->id_poll_answer = $id_answer;
                    $vote->option = (string) $answer['option'];
                    $vote->ip = Yii::$app->request->getUserIP();
                    $vote->save();
                }
            } elseif (isset($question['option']) && !empty($question['option'])) {
                $vote = new Vote();
                $vote->id_poll_question = $id_poll_question;
                $vote->option = $question['option'];
                $vote->ip = Yii::$app->request->getUserIP();
                $vote->save();
            } else {
                foreach ($question['id_answers'] as $id_answer) {
                    $vote = new Vote();
                    $vote->id_poll_question = $id_poll_question;
                    $vote->id_poll_answer = $id_answer;
                    $vote->ip = Yii::$app->request->getUserIP();
                    $vote->save();
                }
            }
        }

        return true;
    }
}
