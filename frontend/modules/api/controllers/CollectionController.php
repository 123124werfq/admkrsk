<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\models\Collection;
use frontend\modules\api\models\CollectionRecord;
use frontend\modules\api\models\search\CollectionSearch;
use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;

/**
 * Collection controller for the `api` module
 */
class CollectionController extends Controller
{
    /**
     * Lists all Collection models.
     * @param $alias
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($alias)
    {
        if (!Collection::find()->where(['alias' => $alias])->exists()) {
            throw new NotFoundHttpException();
        }

        $searchModel = new CollectionSearch(['alias' => $alias]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $dataProvider;
    }

    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($alias, $id)
    {
        return $this->findModel($id);
    }

    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollectionRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CollectionRecord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
