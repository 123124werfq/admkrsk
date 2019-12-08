<?php

namespace backend\controllers;

use Yii;
use common\models\FormElement;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormElementController implements the CRUD actions for FormElement model.
 */
class FormElementController extends Controller
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
     * Lists all FormElement models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FormElement::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FormElement model.
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
     * Creates a new FormElement model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_row)
    {
        $model = new FormElement();

        $model->id_row = $id_row;
        $model->ord = Yii::$app->db->createCommand("SELECT count(*) FROM form_element WHERE id_row = $id_row")->queryScalar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if (!Yii::$app->request->isAjax)
                return $this->redirect(['form/view', 'id' => $model->row->id_form]);
            else
                return $this->renderPartial('/form/_element',['element'=>$model]);
        }

        if (Yii::$app->request->isAjax)
        {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset'=>false,
                'yii\web\YiiAsset'=>false,
            ];

            return $this->renderAjax('_form',['model' => $model]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FormElement model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax)
        {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset'=>false,
                'yii\web\YiiAsset'=>false,
            ];
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if (!Yii::$app->request->isAjax)
            {
                $model->save();
                return $this->redirect(['form/view', 'id' => $model->row->id_form]);
            }
            else
                return $this->renderAjax('_form',['model' => $model,'id_form'=>$model->row->id_form]);
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('_form',['model' => $model,'id_form'=>$model->row->id_form]);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FormElement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_form = $model->row->id_form;
        $model->delete();

        return $this->redirect(['form/view', 'id' => $id_form]);
    }

    /**
     * Finds the FormElement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FormElement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FormElement::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');
        $parents = Yii::$app->request->post('parents');

        foreach ($ords as $key => $id)
            Yii::$app->db->createCommand()->update('form_element',['ord'=>$key,'id_row'=>$parents[$key]],['id_element'=>$id])->execute();
    }
}
