<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\FaqCategory;
use backend\models\search\FaqCategorySearch;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\validators\NumberValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * FaqCategoryController implements the CRUD actions for FaqCategory model.
 */
class FaqCategoryController extends Controller
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
                        'roles' => ['backend.faqCategory.list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'tree'],
                        'roles' => ['backend.faqCategory.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.faqCategory.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.faqCategory.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.faqCategory.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.faqCategory.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.faqCategory.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.faqCategory.log.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => FaqCategory::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.faqCategory.log.restore', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => FaqCategory::class,
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
                'modelClass' => FaqCategory::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => FaqCategory::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => FaqCategory::class,
            ],
        ];
    }

    /**
     * Search Collection models.
     * @param string $q
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionList($q='')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = FaqCategory::find();

        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere([
                'or',
                ['id_faq_category' => $q],
                ['ilike', 'title', $q],
            ]);
        } elseif (!empty($q)) {
            $query->andWhere(['ilike', 'title', $q]);
        }

        $results = [];
        foreach ($query->limit(10)->all() as $category) {
            /* @var FaqCategory $category */
            $results[] = [
                'id' => $category->id_faq_category,
                'text' => $category->title,
            ];
        }

        return ['results' => $results];
    }

    /**
     * Lists all FaqCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FaqCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionTree()
    {
        $root = FaqCategory::find()->roots()->one();
        $children = $root->children()->all();
        return $this->render('tree', ['children' => $children]);
    }

    /**
     * Displays a single FaqCategory model.
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
     * Creates a new FaqCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_faq_category = null)
    {
        $model = new FaqCategory();
        $model->id_parent = $id_faq_category;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->id_parent) {
                $parent = $this->findModel($model->id_parent);
            } else  {
                $parent = FaqCategory::find()->roots()->one();
            }

            if ($model->appendTo($parent)) {
                $model->createAction(Action::ACTION_CREATE);
                return $this->redirect(['view', 'id' => $model->id_faq_category]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FaqCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->depth === 0) {
            Yii::$app->session->setFlash('error', 'Нельзя редактировать корневую категорию');
            return $this->redirect(['index']);
        }

        /* @var FaqCategory $parent */
        $parent = $model->parents(1)->one();
        if ($parent && $parent->depth) {
            $model->id_parent = $parent->id_faq_category;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($parent && $parent->id_faq_category != $model->id_parent) {
                if ($model->id_parent) {
                    $parentNew = FaqCategory::findOne($model->id_parent);
                } else  {
                    $parentNew = FaqCategory::find()->roots()->one();
                }

                if ($parentNew) {
                    $model->appendTo($parentNew);
                }
            }

            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_faq_category]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FaqCategory model.
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

        if ($model->depth === 0) {
            Yii::$app->session->setFlash('error', 'Нельзя удалить корневую категорию');
            return $this->redirect(['index']);
        }

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
     * Finds the FaqCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FaqCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = FaqCategory::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
