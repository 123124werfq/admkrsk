<?php

namespace backend\controllers;

use common\models\Action;
use common\models\Question;
use common\models\Vote;
use common\modules\log\models\Log;
use Yii;
use common\models\Poll;
use backend\models\search\PollSearch;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii2tech\spreadsheet\Spreadsheet;

/**
 * PollController implements the CRUD actions for Poll model.
 */
class PollController extends Controller
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
                        'roles' => ['backend.poll.index'],
                        'roleParams' => [
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.poll.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['export'],
                        'roles' => ['backend.poll.export'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.poll.create'],
                        'roleParams' => [
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create-question'],
                        'roles' => ['backend.poll.questionCreate'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id_poll'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.poll.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-question'],
                        'roles' => ['backend.poll.questionUpdate'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($question = Question::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $question->id_poll;
                                }
                                return null;
                            },
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.poll.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete-question'],
                        'roles' => ['backend.poll.questionDelete'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($question = Question::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $question->id_poll;
                                }
                                return null;
                            },
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.poll.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.poll.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.poll.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Poll::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history-question'],
                        'roles' => ['backend.poll.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Question::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log-question'],
                        'roles' => ['backend.poll.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Question::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore-question'],
                        'roles' => ['backend.poll.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Question::class,
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
                'modelClass' => Poll::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Poll::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Poll::class,
            ],
            'history-question' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => Question::class,
                'parent' => [
                    'relation' => 'poll',
                    'history' => 'history-question',
                    'log' => 'log-question',
                    'restore' => 'restore-question',
                ],
            ],
            'log-question' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Question::class,
                'parent' => [
                    'relation' => 'poll',
                    'history' => 'history-question',
                    'log' => 'log-question',
                    'restore' => 'restore-question',
                ],
            ],
            'restore-question' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Question::class,
                'parent' => [
                    'relation' => 'poll',
                    'history' => 'history-question',
                    'log' => 'log-question',
                    'restore' => 'restore-question',
                ],
            ],
        ];
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);

        $exporter = new Spreadsheet([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->getVotes(),
            ]),
            'columns' => [
                'id_poll_vote',
                'id_poll_question',
                [
                    'attribute' => 'id_poll_question',
                    'value' => function (Vote $model) {
                        return $model->question->question;
                    },
                ],
                'id_poll_answer',
                [
                    'attribute' => 'id_poll_answer',
                    'value' => function (Vote $model) {
                        return $model->answer->answer;
                    },
                ],
                'option',
                'ip',
                'created_by',
                'created_at',
            ],
        ]);

        $exporter->render()->send('poll_' . $model->id_poll . '_' . Yii::$app->formatter->asDatetime(time(), 'yyyyMMdd_HHmmss') . '.xls');
    }

    /**
     * Lists all Poll models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PollSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Poll model.
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
     * Creates a new Poll model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Poll();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->logUserAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_poll]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Question model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param integer $id_poll
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionCreateQuestion($id_poll)
    {
        $poll = $this->findModel($id_poll);
        $model = new Question(['id_poll' => $poll->id_poll]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->logUserAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_poll]);
        }

        return $this->render('create_question', [
            'model' => $model,
            'poll' => $poll,
        ]);
    }

    /**
     * Updates an existing Poll model.
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
            $model->logUserAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_poll]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Question model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateQuestion($id)
    {
        $model = $this->findModelQuestion($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->logUserAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_poll]);
        }

            return $this->render('update_question', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Poll model.
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
            $model->logUserAction(Action::ACTION_DELETE);
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
            $model->logUserAction(Action::ACTION_UNDELETE);
        }

        return $this->redirect(['index', 'archive' => 1]);
    }

    /**
     * Deletes an existing Poll model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDeleteQuestion($id)
    {
        $model = $this->findModelQuestion($id);

        if ($model->delete()) {
            $model->logUserAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['view', 'id' => $model->id_poll]);
    }

    /**
     * Finds the Poll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Poll the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Poll::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Poll model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Question the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelQuestion($id)
    {
        if (($model = Question::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
