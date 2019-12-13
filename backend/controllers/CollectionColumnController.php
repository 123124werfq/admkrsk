<?php

namespace backend\controllers;

use Yii;
use common\models\CollectionColumn;
use common\models\Collection;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CollectionColumnController implements the CRUD actions for CollectionColumn model.
 */
class CollectionColumnController extends Controller
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
     * Lists all CollectionColumn models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $collection = Collection::findOne($id);

        if (empty($collection))
            throw new NotFoundHttpException('The requested page does not exist.');

        $dataProvider = new ActiveDataProvider([
            'query' => CollectionColumn::find()->where(['id_collection'=>$collection->id_collection]),
            'sort' => [
                'defaultOrder' => [
                    'ord' => SORT_ASC
                ]
            ]
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'collection'=>$collection,
        ]);
    }

    /**
     * Displays a single CollectionColumn model.
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
     * Creates a new CollectionColumn model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new CollectionColumn();
        $model->id_collection = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id_collection]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CollectionColumn model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            return $this->redirect(['index', 'id' => $model->id_collection]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CollectionColumn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionList($q,$id_collection)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = CollectionColumn::find();

        $query->andWhere([
            ['id_collection' => $id_collection],
            ['ilike', 'name', $q],
        ]);

        $results = [];

        foreach ($query->all() as $column) {
            /* @var Collection $collection */
            $results[] = [
                'id' => $column->id_column,
                'text' => $column->name,
            ];
        }

        return ['results' => $results];
    }

    /**
     * Finds the CollectionColumn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollectionColumn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CollectionColumn::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');

        foreach ($ords as $key => $id)
            Yii::$app->db->createCommand()->update('db_collection_column',['ord'=>$key],['id_column'=>$id])->execute();
    }
}
