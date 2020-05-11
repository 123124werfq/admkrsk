<?php


namespace common\jobs;


use common\base\Job;
use common\models\Opendata;
use Exception;
use Throwable;
use yii\queue\Queue;
use yii\queue\RetryableJobInterface;

class OpendataExportJob extends Job implements RetryableJobInterface
{
    public $id_opendata;

    /**
     * @param Queue $queue which pushed and is handling the job
     * @return void|mixed result of the job execution
     * @throws Exception
     */
    public function execute($queue)
    {
        if (($opendata = Opendata::findOne($this->id_opendata)) !== null) {
            $opendata->export();
        }
    }

    /**
     * @return int time to reserve in seconds
     */
    public function getTtr()
    {
        return 10 * 60;
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
