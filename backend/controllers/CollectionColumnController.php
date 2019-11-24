<?php

namespace backend\controllers;

use Yii;
use common\models\CollectionColumn;
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
        $dataProvider = new ActiveDataProvider([
            'query' => CollectionColumn::find()->where(['id_collection'=>$id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
            return $this->redirect(['view', 'id' => $model->id_column]);
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
            $newColumns = $model->getColumns()->indexBy('id_column')->all();

            if (isset($newColumns[$column->id_column]) && $column->type != $newColumns[$column->id_column]->type)
            {
                if ($newColumns[$column->id_column]->type == CollectionColumn::TYPE_DATE || $newColumns[$column->id_column]->type == CollectionColumn::TYPE_DATETIME)
                {
                    $values = Yii::$app->db->createCommand("SELECT * FROM db_collection_value WHERE id_column = $column->id_column")->queryAll();

                    $collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);

                    foreach ($values as $key => $value)
                    {
                        if (!is_numeric($value['value']))
                        {
                            Yii::$app->db->createCommand()->update('db_collection_value',['value'=>strtotime($value['value'])],['id_column'=>$column->id_column,'id_record'=>$value['id_record']])->execute();

                            $collection->update(['id_record'=>$value['id_record']],[$value['id_column']=>strtotime($value['value'])]);
                        }
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id_column]);
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
}
