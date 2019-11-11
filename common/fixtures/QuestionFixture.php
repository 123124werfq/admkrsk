<?php

namespace common\fixtures;

use common\base\ActiveFixture;

class QuestionFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Question';
    public $depends = [
        'common\fixtures\AnswerFixture',
    ];
}
