<?php

namespace backend\controllers;

use backend\models\forms\UserRoleForm;
use Yii;
use common\models\UserRole;
use backend\models\search\UserRoleSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserRoleController implements the CRUD actions for UserRole model.
 */
class UserRoleController extends Controller
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
                        'roles' => ['backend.userRole.index'],
                        'roleParams' => [
                            'class' => UserRole::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.userRole.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserRole::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['assign'],
                        'roles' => ['backend.userRole.assign'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserRole::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['revoke'],
                        'roles' => ['backend.userRole.revoke'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => UserRole::class,
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
     * Lists all UserRole models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserRoleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserRole model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $userRoleForm = new UserRoleForm(['name' => $model->name]);
        $assign = false;

        return $this->render('view', [
            'model' => $model,
            'userRoleForm' => $userRoleForm,
            'assign' => $assign,
        ]);
    }

    /**
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        $userRoleForm = new UserRoleForm(['name' => $model->name]);
        $assign = false;

        if ($userRoleForm->load(Yii::$app->request->post())) {
            $assign = $userRoleForm->assign();
        }

        return $this->render('view', [
            'model' => $model,
            'userRoleForm' => $userRoleForm,
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
        $userRoleForm = new UserRoleForm(['name' => $model->name]);
        $revoke = false;

        if ($userRoleForm->load(Yii::$app->request->post())) {
            $revoke = $userRoleForm->revoke();
        }

        return $this->render('view', [
            'model' => $model,
            'userRoleForm' => $userRoleForm,
            'revoke' => $revoke,
        ]);
    }

    /**
     * Finds the UserRole model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return UserRole the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserRole::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
