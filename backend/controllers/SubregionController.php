<?php

namespace backend\controllers;

use common\models\Action;
use Yii;
use common\models\Subregion;
use backend\models\search\SubregionSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\validators\NumberValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException as NotFoundHttpExceptionAlias;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SubregionController implements the CRUD actions for Subregion model.
 */
class SubregionController extends Controller
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

    public function actions()
    {
        return [
            'history' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => Subregion::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Subregion::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Subregion::class,
            ],
        ];
    }

    /**
     * Search Subregion models.
     * @param string $q
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionList($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Subregion::find();

        $q = trim($q);
        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere(['id_subregion' => $q]);
        } else {
            $query->andWhere(['ilike', 'name', $q]);
        }

        $results = [];
        foreach ($query->limit(10)->all() as $subregion) {
            /* @var Subregion $subregion */
            $results[] = [
                'id' => $subregion->id_subregion,
                'text' => $subregion->name,
            ];
        }

        return ['results' => $results];
    }

    /**
     * Lists all Subregion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubregionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subregion model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpExceptionAlias if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Subregion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subregion();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => $model->id_subregion]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Subregion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpExceptionAlias if the model cannot be found
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_subregion]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Subregion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpExceptionAlias if the model cannot be found
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
     * Finds the Subregion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Subregion the loaded model
     * @throws NotFoundHttpExceptionAlias if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Subregion::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpExceptionAlias('The requested page does not exist.');
    }
}
