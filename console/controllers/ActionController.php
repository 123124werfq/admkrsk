<?php

namespace console\controllers;

use common\models\Action;
use Yii;
use yii\console\Controller;

class ActionController extends Controller
{
    public function actionIndex()
    {
        $models = [
            'common\models\Page'=>[
                'actions'=>[
                    Action::ACTION_VIEW
                ]
            ],
            'common\models\News'=>[
                'actions'=>[
                    Action::ACTION_VIEW
                ]
            ],
        ];

        $date_begin = mktime(date('H'),0,0);
        $date_end = mktime(date('H'),60,0);

        foreach ($models as $modelclass => $model)
        {
            foreach ($model['actions'] as $key => $action)
            {
                $sql = "SELECT count(*) as cnt, model_id, model, action
                            FROM action
                            WHERE
                                model = :model,
                            AND action IN (:action)
                            AND create_at >= $date_begin
                            AND create_at >= $date_end
                            GROUP BY model_id, model, action";

                $value = Yii::$app->logDb->createCommand($sql)->bindValues([':model'=>$modelclass,':action'=>$action])->queryRow();


            }
        }

    }
}


