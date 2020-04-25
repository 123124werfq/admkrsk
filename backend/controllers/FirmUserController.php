<?php

namespace backend\controllers;

use Yii;
use common\models\FirmUser;
use backend\models\search\FirmUserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FirmUserController implements the CRUD actions for FirmUser model.
 */
class FirmUserController extends Controller
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

    /**
     * Lists all FirmUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FirmUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FirmUser model.
     * @param integer $id_record
     * @param integer $id_user
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id_record, $id_user)
    {
        return $this->render('view', [
            'model' => $this->findModel($id_record, $id_user),
        ]);
    }

    /**
     * Creates a new FirmUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FirmUser();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_record' => $model->id_record, 'id_user' => $model->id_user]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FirmUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_record
     * @param integer $id_user
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id_record, $id_user)
    {
        $model = $this->findModel($id_record, $id_user);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id_record' => $model->id_record, 'id_user' => $model->id_user]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FirmUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id_record
     * @param integer $id_user
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id_record, $id_user)
    {
        $this->findModel($id_record, $id_user)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FirmUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id_record
     * @param integer $id_user
     * @return FirmUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id_record, $id_user)
    {
        if (($model = FirmUser::findOne(['id_record' => $id_record, 'id_user' => $id_user])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
