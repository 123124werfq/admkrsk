<?php

namespace common\jobs;

use common\base\Job;
use common\models\Action;
use common\models\Statistic;
use Throwable;
use Exception;
use Yii;
use yii\db\Expression;
use yii\queue\RetryableJobInterface;

class StatisticJob extends Job implements RetryableJobInterface
{
    /**
     * @inheritdoc
     * @throws Exception
     */
    public function execute($queue)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $isExists = Statistic::find()->exists();

            $query = Action::find()
                ->select([
                    'model',
                    'model_id',
                    'count' => new Expression('COUNT(id)'),
                ])
                ->where(['action' => 'view'])
                ->groupBy(['model', 'model_id'])
                ->asArray();

            foreach ($query->each() as $action) {
                Statistic::createOrUpdate($action);
            }

            $query = Action::find()
                ->select([
                    'model',
                    'model_id',
                    'count' => new Expression('COUNT(id)'),
                    'year' => new Expression("date_part('year', to_timestamp(max(created_at)))"),
                ])
                ->where([
                    'and',
                    ['action' => 'view'],
                    $isExists ? ['>=', 'created_at', mktime(0, 0 ,0, 1, 1)] : [],
                ])
                ->groupBy(['model', 'model_id'])
                ->asArray();

            foreach ($query->each() as $action) {
                Statistic::createOrUpdate($action);
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 60 * 10;
    }

    /**
     * @param int $attempt number
     * @param Exception|Throwable $error from last execute of the job
     * @return bool
     */
    public function canRetry($attempt, $error)
    {
        return false;
    }
}
