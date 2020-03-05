<?php

namespace common\bootstrap;

use common\models\Action;
use common\models\User;
use common\modules\log\models\Log;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;

class CommonBootstrap implements BootstrapInterface
{

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        Event::on(ActiveRecord::class, ActiveRecord::EVENT_AFTER_INSERT, function ($event) {
            if (!$event->sender instanceof Action &&
                !$event->sender instanceof Log
            ) {
                User::rbacCacheInvalidate();
            }
        });
    }
}
