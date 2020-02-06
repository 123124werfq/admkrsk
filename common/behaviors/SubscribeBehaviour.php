<?php

namespace common\behaviors;

use common\models\News;
use common\models\SubscriberNotifyManager;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;

class SubscribeBehaviour extends Behavior
{
    /**
     * @var News
     */
    public $owner;

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_INSERT => 'notify'
        ];
    }

    public function notify()
    {
        $manager = new SubscriberNotifyManager();
        $manager->loggingNotify($this->owner);
        $manager->notify();
    }
}