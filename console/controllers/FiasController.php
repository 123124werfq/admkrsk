<?php

namespace console\controllers;

use common\jobs\FiasImportJob;
use common\models\House;
use Yii;
use yii\console\Controller;

class FiasController extends Controller
{
    public $region = 24;
    public $limit = 1000;

    private $lastVersion = null;

    public function options($actionID)
    {
        return array_merge(parent::options($actionID),
            $actionID == 'update' ? ['region'] : [],
            $actionID == 'update-location' ? ['limit'] : []
        );
    }

    public function actionUpdateFullname()
    {
        foreach (House::find()->each() as $house) {
            /* @var House $house */
            $house->updateAttributes(['fullname' => $house->getFullName()]);
        }
    }

    public function actionUpdateLocation()
    {
        $houseQuery = House::find()
            ->where(['sputnik_updated_at' => null])
            ->limit($this->limit);

        foreach ($houseQuery->each() as $house) {
            /* @var House $house */
            $house->updateLocation();
        }
    }

    public function actionUpdate()
    {
        $jobId = FiasImportJob::getJobId();

        if (!$jobId || (!Yii::$app->queue->isWaiting($jobId) && !Yii::$app->queue->isReserved($jobId) && Yii::$app->queue->isDone($jobId))) {
            $jobId = Yii::$app->queue->push(new FiasImportJob());

            FiasImportJob::saveJobId($jobId);

            $this->stdout('Запущено обновление fias ID:'. $jobId . PHP_EOL);
        } else {
            $this->stdout('Обновление fias уже выполняется' . PHP_EOL);
        }
    }
}
