<?php

namespace console\controllers;

use common\models\Action;
use common\models\Statistic;
use Yii;
use yii\console\Controller;
use yii\db\Expression;

class StatisticController extends Controller
{
    public function actionIndex()
    {
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
    }
}
