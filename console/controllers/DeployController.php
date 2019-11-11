<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class DeployController extends Controller
{
    public function actionIndex()
    {
        Yii::$app->runAction('migrate/up', ['interactive' => $this->interactive]);

        Yii::$app->runAction('migrate-log/up', ['interactive' => $this->interactive]);

        Yii::$app->runAction('rbac/init', ['interactive' => $this->interactive]);
    }
}
