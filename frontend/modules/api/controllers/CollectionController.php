<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\base\Controller;
use frontend\modules\api\models\Collection;
use frontend\modules\api\models\CollectionRecord;
use frontend\modules\api\models\search\CollectionSearch;
use Yii;
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
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionIndex($alias)
    {
        $collection = $this->findCollectionModel($alias);

        if ($collection->is_authenticate) {
            $this->checkAccess();
        }

        $searchModel = new CollectionSearch(['alias' => $collection->alias]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $dataProvider;
    }

    /**
     * Displays a single Collection model.
     * @param $alias
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function actionView($alias, $id)
    {
        $record = $this->findCollectionRecordModel($id);

        if ($record->collection->is_authenticate) {
            $this->checkAccess();
        }

        return $record;
    }

    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollectionRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCollectionRecordModel($id)
    {
        if (($model = CollectionRecord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }

    /**
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $alias
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCollectionModel($alias)
    {
        if (($model = Collection::findOne(['alias' => $alias])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Страница не найдена.');
    }
}
