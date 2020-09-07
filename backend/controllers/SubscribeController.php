<?php

namespace backend\controllers;

use common\models\Subscriber;
use yii\web\Controller;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

use common\models\AuthEntity;
use common\models\GridSetting;

use Yii;


class SubscribeController extends Controller
{
    const grid = 'subs-grid';

    public function behaviors()
    {
        return [

        ];
    }

    public function actionIndex()
    {
        $total = Subscriber::find()->orderBy('email')->all();
        $query = Subscriber::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $params['pageSize'] ?? 10
            ],
        ]); 

        $grid = GridSetting::findOne([
            'class' => static::grid,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('statistic', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);        
    }



}