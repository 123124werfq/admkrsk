<?php

namespace backend\controllers;

use common\models\GridSetting;
use Yii;
use common\models\Integration;
use yii\data\ActiveDataProvider;
use yii\web\Controller;


class IntegrationsController extends Controller
{
    const grid = 'integrations-grid';

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Integration::find()->orderBy('id_integration DESC'),
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

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }

}
