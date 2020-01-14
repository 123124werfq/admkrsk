<?php

namespace backend\controllers;

use common\models\Action;
use Yii;
use common\models\Region;
use backend\models\search\RegionSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RegionController implements the CRUD actions for Region model.
 */
class RegionController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'history' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => Region::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Region::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Region::class,
            ],
        ];
    }

    /**
     * Lists all Region models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RegionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Region model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Region model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Region();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_region]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Region model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_region]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Region model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            $model->createAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUndelete($id)
    {
        $model = $this->findModel($id);

        if ($model->restore()) {
            $model->createAction(Action::ACTION_UNDELETE);
        }

        return $this->redirect(['index', 'archive' => 1]);
    }

    /**
     * Finds the Region model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Region the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Region::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
