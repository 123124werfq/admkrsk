<?php

namespace backend\controllers;

use Yii;
use common\models\CollectionColumn;
use common\models\Collection;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
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
            ],
            'pagination' => [
                'pageSize' => 9000,
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
        $model->type = CollectionColumn::TYPE_CUSTOM;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if ($model->isCustom() && !empty($model->template))
                $this->updateCustomValues($model);

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
            if ($model->isCustom() && !empty($model->template))
                $this->updateCustomValues($model);

            return $this->redirect(['index', 'id' => $model->id_collection]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    protected function updateCustomValues($model)
    {
        $collection = $column->collection;
        $mongoCollection = Yii::$app->mongodb->getCollection('collection'.$collection->id_collection);

        $records = $collection->getData([],true);

        foreach ($records as $id_record => $data)
        {
            $dataMongo = ['col'.$model->id_column => CollectionColumn::renderCustomValue($model->template,$data)];

            $mongoCollection->update(['id_record' => $id_record], $dataMongo);
        }
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
        $model = $this->findModel($id);
        $id = $model->id_collection;
        $model->delete();

        return $this->redirect(['index','id'=>$id]);
    }

    public function actionList($q='',$id_collection)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = CollectionColumn::find();

        $query->andWhere(['id_collection' => $id_collection]);

        if (!empty($q))
            $query->andWhere('or',['ilike', 'name', $q],['ilike', 'alias', $q]);

        $results = [];

        foreach ($query->all() as $column) {

            $results[] = [
                'id' => $column->id_column,
                'text' => $column->name.' ('.$column->alias.')',
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
