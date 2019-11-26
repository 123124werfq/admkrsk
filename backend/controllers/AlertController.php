<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\Alert;
use backend\models\search\AlertSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AlertController implements the CRUD actions for Alert model.
 */
class AlertController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.alert.index'],
                        'roleParams' => [
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.alert.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.alert.create'],
                        'roleParams' => [
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.alert.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['backend.alert.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.alert.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.alert.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Alert::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.alert.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Alert::class,
                        ],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Alert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AlertSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alert model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Alert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Alert();
        $model->date_begin = time();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['index', 'id' => $model->id_alert]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Alert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['index', 'id' => $model->id_alert]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Alert model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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
     * Finds the Alert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Alert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alert::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
