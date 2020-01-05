<?php

namespace backend\controllers;

use common\models\Action;
use Yii;
use common\models\ServiceTarget;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use backend\models\search\ServiceTargetSearch;
use yii\web\Response;

/**
 * ServiceTargetController implements the CRUD actions for ServiceTarget model.
 */
class ServiceTargetController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ServiceTarget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceTargetSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*if (!empty($id))
            $query->where(['id_service'=>$id]);*/

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'=>$searchModel,
        ]);
    }

    /**
     * Displays a single ServiceTarget model.
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
     * Creates a new ServiceTarget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new ServiceTarget();
        $model->id_service = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['index', 'id' => $model->id_service]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ServiceTarget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()){
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['index', 'id' => $model->id_service]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ServiceTarget model.
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
        $id_service = $model->id_service;

        if ($model->delete()) {
            $model->createAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['service/view', 'id' => $id_service]);
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
     * Finds the ServiceTarget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceTarget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = ServiceTarget::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
