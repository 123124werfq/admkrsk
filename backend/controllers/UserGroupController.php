<?php

namespace backend\controllers;

use backend\models\forms\UserGroupForm;
use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\UserGroup;
use backend\models\search\UserGroupSearch;
use yii\filters\AccessControl;
use yii\validators\NumberValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * UserGroupController implements the CRUD actions for UserGroup model.
 */
class UserGroupController extends Controller
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
                        'roles' => ['backend.userGroup.list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.userGroup.index'],
                        'roleParams' => [
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.userGroup.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.userGroup.create'],
                        'roleParams' => [
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.userGroup.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['backend.userGroup.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assign'],
                        'roles' => ['backend.userGroup.assign'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['revoke'],
                        'roles' => ['backend.userGroup.revoke'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.userGroup.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.userGroup.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => UserGroup::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.userGroup.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => UserGroup::class,
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
     * Search User models.
     * @param string $q
     * @return mixed
     */
    public function actionList($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = UserGroup::find();

        $q = trim($q);
        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere(['id_user_group' => $q]);
        } else {
            $query->andWhere(['ilike', 'name', $q]);
        }

        $results = [];
        foreach ($query->limit(10)->all() as $userGroup) {
            /* @var UserGroup $userGroup */
            $results[] = [
                'id' => $userGroup->id_user_group,
                'text' => $userGroup->name,
            ];
        }

        return ['results' => $results];
    }

    /**
     * Lists all UserGroup models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserGroup model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $userGroupForm = new UserGroupForm(['id_user_group' => $model->id_user_group]);
        $assign = false;

        return $this->render('view', [
            'model' => $model,
            'userGroupForm' => $userGroupForm,
            'assign' => $assign,
        ]);
    }

    /**
     * Creates a new UserGroup model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UserGroup();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->logUserAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_user_group]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UserGroup model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->logUserAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_user_group]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserGroup model.
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
            $model->logUserAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['index']);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        $userGroupForm = new UserGroupForm(['id_user_group' => $model->id_user_group]);
        $assign = false;

        if ($userGroupForm->load(Yii::$app->request->post())) {
            $assign = $userGroupForm->assign();
        }

        return $this->render('view', [
            'model' => $model,
            'userGroupForm' => $userGroupForm,
            'assign' => $assign,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRevoke($id)
    {
        $model = $this->findModel($id);
        $userGroupForm = new UserGroupForm(['id_user_group' => $model->id_user_group]);
        $revoke = false;

        if ($userGroupForm->load(Yii::$app->request->post())) {
            $revoke = $userGroupForm->revoke();
        }

        return $this->render('view', [
            'model' => $model,
            'userGroupForm' => $userGroupForm,
            'revoke' => $revoke,
        ]);
    }

    /**
     * Finds the UserGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UserGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserGroup::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
