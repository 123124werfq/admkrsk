<?php

namespace backend\controllers;

use common\models\Action;
use common\models\GridSetting;
use common\modules\log\models\Log;
use Yii;
use common\models\Gallery;
use backend\models\search\GallerySearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * GalleryController implements the CRUD actions for Gallery model.
 */
class GalleryController extends Controller
{
    const grid = 'gallery-grid';

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
                        'actions' => ['index','get-gallery'],
                        'roles' => ['backend.gallery.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.gallery.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.gallery.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.gallery.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.gallery.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.gallery.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.gallery.log.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Gallery::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.gallery.log.restore', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Gallery::class,
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
     * Lists all Gallery models.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = new GallerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $grid = GridSetting::findOne([
            'class' => static::grid,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
        ]);
    }


    public function actionGetGallery()
    {
        $records = Gallery::find()->all();

        $output = [];
        foreach ($records as $key => $data)
            $output[] = ['text'=>$data->name,'value'=>(string)$data->id_gallery];

        return json_encode($output);
    }

    /**
     * Displays a single Gallery model.
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
     * Creates a new Gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Gallery();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['index', 'id' => $model->id_gallery]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Gallery model.
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
            return $this->redirect(['index', 'id' => $model->id_gallery]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Gallery model.
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
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
