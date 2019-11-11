<?php

namespace console\controllers;

use common\models\Opendata;
use Yii;
use yii\console\Controller;

class OpendataController extends Controller
{
    public function actionIndex()
    {
        foreach (Opendata::find()->each() as $opendata) {
            /* @var Opendata $opendata */
            $opendata->export();
        }
    }
}
