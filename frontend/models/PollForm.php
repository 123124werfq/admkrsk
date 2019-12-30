<?php

namespace frontend\models;

use common\base\CompositeForm;
use common\models\Poll;
use common\models\Question;
use common\models\Vote;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * @property VoteForm[] $votes
 */
class PollForm extends CompositeForm
{
    /**
     * @var Poll
     */
    public $poll;

    public function __construct(Poll $poll, $config = [])
    {
        $this->poll = $poll;

        $votes = [];
        foreach ($poll->getQuestions()->orderBy(['ord' => SORT_ASC])->all() as $question) {
            /* @var Question $question */
            $votes[$question->id_poll_question] = new VoteForm($question);
        }
        $this->votes = $votes;

        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['poll', 'validatePoll'],
        ];
    }

    /**
     * @param string $attribute
     */
    public function validatePoll($attribute): void
    {
        if ($this->poll->isExpired()) {
            $this->addError('poll', 'Голосование закончилось');
        }

        if ($this->poll->isPassed()) {
            $this->addError('poll', 'Вы уже голосовали');
        }
    }

    protected function internalForms(): array
    {
        return [
            'votes',
        ];
    }

    /**
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $save = true;

                foreach ($this->votes as $voteForm) {
                    if (!empty($voteForm->option)) {
                        $vote = new Vote();
                        $vote->id_poll_question = $voteForm->question->id_poll_question;
                        $vote->option = (string) $voteForm->option;
                        $vote->ip = Yii::$app->request->getUserIP();
                        $save = $vote->save() && $save;

                        $votes[] = $vote;
                    } elseif (!empty($voteForm->answer_ids)) {
                        foreach ($voteForm->answer_ids as $index => $answer_id) {
                            $vote = new Vote();
                            $vote->id_poll_question = $voteForm->question->id_poll_question;
                            $vote->id_poll_answer = $answer_id;
                            $vote->option = $voteForm->question->type == Question::TYPE_RANGING ? (string) $index : null;
                            $vote->ip = Yii::$app->request->getUserIP();
                            $save = $vote->save() && $save;

                            $votes[] = $vote;
                        }
                    }
                }

                if (!$save) {
                    $transaction->rollBack();
                    return false;
                }

                $transaction->commit();
                $this->poll->setCookie();
                return true;
            } catch (\Exception $exception) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка сохранения голосования');
            }
        }

        return false;
    }
}
