<?php

namespace common\fixtures;

use common\base\ActiveFixture;

class PollFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Poll';
    public $depends = [
        'common\fixtures\QuestionFixture',
        'common\fixtures\VoteFixture',
    ];
}
