<?php

namespace backend\controllers;

use common\models\Action;
use Yii;
use common\models\Form;
use common\models\FormRow;
use common\models\Collection;

use backend\models\search\FormSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormController implements the CRUD actions for Form model.
 */
class FormController extends Controller
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
     * Lists all Form models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FormSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Form model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $rows = $model->rows;

        if (empty($rows))
        {
            $row = new FormRow;
            $row->id_form = $id;
            $row->ord = 0;
            $row->save();

            $rows = [$row];
        }

        return $this->render('view', [
            'model' => $model,
            'rows' => $rows,
        ]);
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Form();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $collection = new Collection;
            $collection->name = $model->name;
            $collection->id_form = $model->id_form;

            if ($collection->save())
            {
                $model->id_collection = $collection->id_collection;
                $model->updateAttributes(['id_collection']);
            }

            $model->createAction(Action::ACTION_CREATE);

            return $this->redirect(['view', 'id' => $model->id_form]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateRow($id_form)
    {
        $form = $this->findModel($id_form);

        $model = new FormRow;
        $model->id_form = $id_form;
        $model->ord = FormRow::find()->where(['id_form'=>$id_form])->count();

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id_form]);
        }

        return $this->redirect(['view', 'id' => $model->id_form]);
    }

    /**
     * Updates an existing Form model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_form]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionGetForm()
    {
        $records = Form::find()->all();

        $output = [];
        foreach ($records as $key => $data)
            $output[] = ['text'=>$data->name,'value'=>(string)$data->id_form];

        return json_encode($output);
    }


    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');

        foreach ($ords as $key => $id)
            Yii::$app->db->createCommand()->update('form_row',['ord'=>$key],['id_row'=>$id])->execute();
    }

    /**
     * Deletes an existing Form model.
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
            $model->createAction(Action::ACTION_DELETE);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Form model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Form the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Form::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
