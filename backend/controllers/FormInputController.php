<?php

namespace backend\controllers;

use Yii;
use common\models\FormInput;
use common\models\FormElement;
use common\models\FormRow;
use common\models\CollectionColumn;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * FormInputController implements the CRUD actions for FormInput model.
 */
class FormInputController extends Controller
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
     * Lists all FormInput models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FormInput::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FormInput model.
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

    public function actionGetValueInput($id)
    {
        $model = $this->findModel($id);

        if ($model->type->type==CollectionColumn::TYPE_SELECT)
        {
            $values = $model->getArrayValues;

            return $this->renderAjax('_input',['values'=>$values]);
        }
    }
    /**
     * Creates a new FormInput model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_row)
    {
        $model = new FormInput();

        $row = FormRow::findOne($id_row);
        $form = $row->form;
        $model->id_form = $form->id_form;
        $model->populateRelation('element', new FormElement);

        if (Yii::$app->request->isAjax)
        {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset'=>false,
                'yii\web\YiiAsset'=>false,
            ];
        }

        if ($model->load(Yii::$app->request->post()) && !Yii::$app->request->isAjax)
        {
            if (Yii::$app->request->isAjax)
                return $this->renderAjax('_form',['model' => $model]);

            if ($model->save())
            {
                // создается элемент формы
                $element = new FormElement;
                $element->load(Yii::$app->request->post());
                $element->id_input = $model->id_input;
                $element->id_row = $id_row;
                $element->ord = Yii::$app->db->createCommand("SELECT count(*) FROM form_element WHERE id_row = $id_row")->queryScalar();
                $element->save();

                // создаем колонку для коллекции
                $column = new CollectionColumn;
                $column->name = $model->name;
                $column->alias = $model->fieldname;
                $column->id_collection = $form->id_collection;
                $column->type = $model->type;

                if ($column->save())
                {
                    $model->id_column = $column->id_column;
                    $model->updateAttributes(['id_column']);
                }
                else
                    print_r($column->errors);

                if (!Yii::$app->request->isAjax)
                    return $this->redirect(['form/view', 'id' => $model->id_form]);
                else
                    return $this->renderPartial('/form/_input',['element'=>$element]);
            }
        }

        if (Yii::$app->request->isAjax)
        {
            return $this->renderAjax('_form',['model' => $model]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing FormInput model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $element = $model->element;
        $element->load(Yii::$app->request->post());

        if (Yii::$app->request->isAjax)
        {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset'=>false,
                'yii\web\YiiAsset'=>false,
            ];
        }


        if ($model->load(Yii::$app->request->post()) && $model->validate() && $element->validate())
        {
            if (Yii::$app->request->isAjax)
                return $this->renderAjax('_form',['model' => $model]);

            if ($model->save() && $element->save())
            {
                if ($model->column->type != $model->type)
                {
                    $model->column->type = $model->type;
                    $model->column->save();
                }

                if ($model->column->alias != $model->fieldname)
                {
                    $model->column->alias = $model->fieldname;
                    $model->column->updateAttributes(['alias']);
                }


                if (!Yii::$app->request->isAjax)
                    return $this->redirect(['form/view', 'id' => $model->id_form]);
            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('_form',['model' => $model]);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing FormInput model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $element = FormElement::findOne(['id_input'=>$id]);

        $input = $this->findModel($id);
        $id_form = $input->id_form;
        $input->delete();
        $element->delete();

        return $this->redirect(['form/view','id'=>$id_form]);
    }

    /**
     * Finds the FormInput model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FormInput the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FormInput::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
