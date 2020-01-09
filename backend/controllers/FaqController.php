<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\Faq;
use backend\models\search\FaqSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
                        'actions' => ['list'],
                        'roles' => ['backend.faq.list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.faq.index'],
                        'roleParams' => [
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.faq.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.faq.create'],
                        'roleParams' => [
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.faq.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.faq.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.faq.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.faq.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Faq::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.faq.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Faq::class,
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

    public function actions()
    {
        return [
            'history' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => Faq::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Faq::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Faq::class,
            ],
        ];
    }

    /**
     * Lists all Faq models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Faq model.
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
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Faq();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_faq]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Faq model.
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
            return $this->redirect(['view', 'id' => $model->id_faq]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Faq model.
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
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
