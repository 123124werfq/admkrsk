<?php

namespace frontend\widgets;

use common\models\Question;
use yii\base\Widget;

class PollWidget extends Widget
{
    public $id_poll_question;
    public $block;
    public $page;

    public function run()
    {
        if (!empty($this->block))
        {
            $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

            if (!empty($blockVars['id_poll_question']->value))
                $this->id_poll_question = $blockVars['id_poll_question']->value;
        }

        if (($question = Question::findOne($this->id_poll_question)) === null)
            return false;

        return $this->render('poll',[
            'question' => $question,
            'blockVars' => $blockVars,
        ]);
    }
}
