<?php

namespace console\controllers;

use common\jobs\OpendataExportJob;
use common\models\Opendata;
use Yii;
use yii\console\Controller;

class OpendataController extends Controller
{
    public function actionIndex()
    {
        foreach (Opendata::find()->each() as $opendata) {
            /* @var Opendata $opendata */
            if ($opendata->isDue()) {
                Yii::$app->queue->push(new OpendataExportJob(['id_opendata' => $opendata->id_opendata]));
            }
        }
    }
}
