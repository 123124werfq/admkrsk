<?php

namespace backend\controllers;

use common\models\GridSetting;
use Yii;
use yii\web\Controller;

class GridController extends Controller
{
    public function actionSaveGridSettings()
    {
        $settings = json_decode(Yii::$app->request->post('json'));
        $class = Yii::$app->request->post('class');
        if ($settings) {
            $gridSettings = GridSetting::findOne([
                'class' => $class,
                'user_id' => Yii::$app->user->id,
            ]);
            if ($gridSettings) {
                $gridSettings->saveOptions($class, $settings);
                return $gridSettings->save();
            } else {
                $gridSettings = new GridSetting();
                $gridSettings->saveOptions($class, $settings);
                return $gridSettings->save();
            }
        }
        return false;
    }
}