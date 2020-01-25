<?php

namespace backend\controllers;

use backend\models\forms\FiasUpdateSettingForm;
use Yii;
use backend\models\search\FiasUpdateHistorySearch;
use yii\web\Controller;

/**
 * FiasUpdateHistoryController implements the CRUD actions for FiasUpdateHistory model.
 */
class FiasUpdateHistoryController extends Controller
{
    /**
     * Lists all FiasUpdateHistory models.
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $settingForm = new FiasUpdateSettingForm();

        if ($settingForm->load(Yii::$app->request->post())) {
            if ($settingForm->save()) {
                Yii::$app->session->setFlash('success', 'Настройки успешно сохранены');
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка при сохранении настроек');
            }
        }

        $searchModel = new FiasUpdateHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'settingForm' => $settingForm,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
