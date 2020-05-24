<?php

namespace console\controllers;

use common\jobs\StatisticJob;
use Yii;
use yii\console\Controller;

class StatisticController extends Controller
{
    public function actionIndex()
    {
        $jobId = StatisticJob::getJobId();

        if (!$jobId || (!Yii::$app->queue->isWaiting($jobId) && !Yii::$app->queue->isReserved($jobId) && Yii::$app->queue->isDone($jobId))) {
            $jobId = Yii::$app->queue->push(new StatisticJob());

            StatisticJob::saveJobId($jobId);

            $this->stdout('Запущено обновление статистики' . PHP_EOL);
        } else {
            $this->stdout('Обновление статистики уже выполняется' . PHP_EOL);
        }
    }
}
