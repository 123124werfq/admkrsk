<?php

namespace backend\controllers;

use common\models\Action;
use common\models\AuthEntity;
use common\modules\log\models\Log;
use Yii;
use common\models\ServiceRubric;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ServiceRubricController implements the CRUD actions for ServiceRubric model.
 */
class ServiceRubricController extends Controller
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
                        'roles' => ['backend.serviceRubric.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.serviceRubric.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.serviceRubric.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.serviceRubric.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.serviceRubric.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.serviceRubric.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.serviceRubric.log.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => ServiceRubric::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.serviceRubric.log.restore', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => ServiceRubric::class,
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
     * Lists all ServiceRubric models.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isAjax)
            return $this->actionOrder();

        if (Yii::$app->request->get('archive')) {
            $query = ServiceRubric::findDeleted();
        } else {
            $query = ServiceRubric::find();
        }

        $recordsQuery = $query->where('id_parent IS NULL');

        if (!Yii::$app->user->can('admin.serviceRubric')) {
            $recordsQuery->andFilterWhere(['id_rub' => AuthEntity::getEntityIds(ServiceRubric::class)]);
        }

        return $this->render('index', [
            'records'=>$recordsQuery->orderBy('ord ASC')->all(),
        ]);
    }

    /**
     * Displays a single ServiceRubric model.
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
     * Creates a new ServiceRubric model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ServiceRubric();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_rub]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ServiceRubric model.
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

            if (!Yii::$app->request->isAjax)
                return $this->redirect(['view', 'id' => $model->id_rub]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ServiceRubric model.
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
     * Finds the ServiceRubric model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceRubric the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = ServiceRubric::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');

        if (!empty($ords) && is_array($ords))
            foreach ($ords as $key => $id)
                Yii::$app->db->createCommand()->update('service_rubric',['ord'=>$key],['id_rub'=>$id])->execute();
    }
}
