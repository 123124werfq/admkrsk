<?php

namespace backend\controllers;

use common\models\Action;
use common\models\GridSetting;
use common\modules\log\models\Log;
use Exception;
use Yii;
use common\models\Form;
use common\models\FormRow;
use common\models\FormElement;
use common\models\FormInput;
use common\models\Collection;
use common\models\CollectionColumn;
use backend\models\search\FormSearch;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * FormController implements the CRUD actions for Form model.
 */
class FormController extends Controller
{
    const grid = 'form-grid';

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
                        'actions' => ['get-form'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.form.index'],
                        'roleParams' => [
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.form.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','create-service','copy','make-doc'],
                        'roles' => ['backend.form.create'],
                        'roleParams' => [
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create-row'],
                        'roles' => ['backend.form.createRow'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id_form'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-row', 'undelete', 'delete-row'],
                        'roles' => ['backend.form.updateRow'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id_form'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','order','assign-form'],
                        'roles' => ['backend.form.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['get-form'],
                        'roles' => ['backend.form.getForm'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['order'],
                        'roles' => ['backend.form.order'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['backend.form.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.form.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.form.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.form.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Form::class,
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
     * Lists all Form models.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionIndex()
    {
        $searchModel = new FormSearch();
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

    /**
     * Displays a single Form model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
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


    public function actionMakeDoc($id_record)
    {
        $record = \common\models\CollectionRecord::findOne($id_record);
        $export_path = $record->collection->form->makeDoc($record);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="word_template.docx"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_path));

        readfile($export_path);
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Form();
        $model->state = 1;

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

    public function actionCreateService($id_service)
    {
        $model = new Form;
        $model->id_service = $id_service;
        $model->state = 1;

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

            return $this->redirect(['view', 'id' => $model->id_form]);
        }

        return $this->render('service',[
            'model'=>$model
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCopy($id)
    {
        $form = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $copyForm = new Form;
            $copyForm->attributes = $form->attributes;
            $copyForm->id_collection = null;
            $copyForm->name = 'Копия - '.$form->name;

            if ($copyForm->save())
            {
                $collection = new Collection;
                $collection->id_form = $copyForm->id_form;
                $collection->name = $copyForm->name;
                $collection->save();

                $copyForm->id_collection = $collection->id_collection;
                $copyForm->updateAttributes(['id_collection']);

                foreach ($form->rows as $rkey => $row)
                {
                    $newRow = new FormRow;
                    $newRow->attributes = $row->attributes;
                    $newRow->id_form = $copyForm->id_form;
                    $newRow->ord = $row->ord;

                    if ($newRow->save())
                    {
                        foreach ($row->elements as $key => $element)
                        {
                            $copyElement = new FormElement;
                            $copyElement->attributes = $element->attributes;
                            $copyElement->id_row = $newRow->id_row;

                            $visibleInputs = [];

                            $newVisibleInputs = [];
                            if (!empty($element->visibleInputs))
                            {
                                foreach ($element->visibleInputs as $vikey => $vinput)
                                    $visibleInputs[$vinput->id_input_visible] = $vinput->values;
                            }

                            if (!empty($element->input))
                            {

                                $newInput = new FormInput;
                                $newInput->attributes = $element->input->attributes;
                                $newInput->id_form = $copyForm->id_form;

                                if (!$newInput->save())
                                    print_r($newInput->errors);

                                if (isset($visibleInputs[$element->input->id_input]))
                                {
                                    $newVisibleInputs[$newInput->id_input] = $visibleInputs[$element->input->id_input];
                                }

                                $copyElement->id_input = $newInput->id_input;

                                $column = new CollectionColumn;
                                $column->name = $newInput->name;
                                $column->alias = $newInput->fieldname;
                                $column->id_collection = $copyForm->id_collection;
                                $column->type = $newInput->type;

                                if (!$column->save())
                                    print_r($column->errors);

                                $newInput->id_column = $column->id_column;
                                $newInput->updateAttributes(['id_column']);
                            }

                            if ($copyElement->save() && !empty($newVisibleInputs))
                            {
                                foreach ($newVisibleInputs as $vikey => $values)
                                {
                                    Yii::$app->db->createCommand()->insert('form_visibleinput',[
                                        'id_element'=>$copyElement->id_element,
                                        'values'=>$values,
                                        'id_input_visible'=>$vikey,
                                    ])->execute();
                                }
                            }

                            if (!empty($element->subForm))
                            {
                                $this->assignForm($element->id_form,'',$copyForm,'',$copyElement);
                            }
                        }
                    }
                    else
                    {
                        print_r($newRow->errors);
                    }
                }

                $transaction->commit();

                return $this->redirect(['view', 'id'=>$copyForm->id_form]);
            }
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param $id_form
     * @param $id_row
     * @param $parentForm
     * @param string $prefix
     * @param null $element
     * @throws Exception
     */
    protected function assignForm($id_form, $id_row, $parentForm, $prefix='', $element=null)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try
        {
            $copyForm = Form::findOne($id_form);

            $subForm = new Form;
            $subForm->is_template = 2;
            $subForm->id_collection = $parentForm->id_collection;
            $subForm->name = $parentForm->name.' '.$copyForm->name;

            if ($subForm->save())
            {
                if (empty($element))
                {
                    $newElement = new FormElement;
                    $newElement->id_row = $id_row;
                    $newElement->ord = Yii::$app->db->createCommand("SELECT count(*) FROM form_element WHERE id_row = $id_row")->queryScalar();
                }
                else
                    $newElement = $element;

                $newElement->id_form = $subForm->id_form;

                if ($newElement->save())
                {
                    foreach ($copyForm->rows as $key => $row)
                    {
                        $newRow = new FormRow;
                        $newRow->attributes = $row->attributes;
                        $newRow->id_form = $subForm->id_form;
                        $newRow->ord = $row->ord;

                        if ($newRow->save())
                        {
                            foreach ($row->elements as $key => $element)
                            {
                                $copyElement = new FormElement;
                                $copyElement->attributes = $element->attributes;
                                $copyElement->id_row = $newRow->id_row;

                                if (!empty($element->input))
                                {
                                    $newInput = new FormInput;
                                    $newInput->attributes = $element->input->attributes;
                                    $newInput->id_form = $parentForm->id_form;
                                    $newInput->fieldname = (!empty($prefix)?$prefix.'_':'').$newInput->fieldname;

                                    if (!$newInput->save())
                                    {
                                        $transaction->rollBack();
                                        print_r($newInput->errors);
                                    }

                                    $copyElement->id_input = $newInput->id_input;

                                    $column = new CollectionColumn;
                                    $column->name = $newInput->name;
                                    $column->alias = $newInput->fieldname;
                                    $column->id_collection = $parentForm->id_collection;
                                    $column->type = $newInput->type;

                                    if (!$column->save())
                                    {
                                        $transaction->rollBack();
                                        print_r($column->errors);
                                    }

                                    $newInput->id_column = $column->id_column;
                                    $newInput->updateAttributes(['id_column']);
                                }

                                $copyElement->save();
                            }
                        }
                        else
                        {
                            $transaction->rollBack();
                            print_r($newRow->errors);
                        }
                    }
                }
                else
                {
                    $transaction->rollBack();
                    print_r($newElement->errors);
                }
            }

            $transaction->commit();
        }
        catch (Exception $e)
        {
            $transaction->rollBack();
            throw $e;
        }
    }

    /**
     * @param $id_row
     * @return string
     * @throws InvalidConfigException
     */
    public function actionAssignForm($id_row)
    {
        $insertRow = FormRow::findOne($id_row);

        $form = new \backend\models\InsertForm;
        $form->id_form_parent = $insertRow->id_form;

        $parentForm = Form::findOne($form->id_form_parent);

        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset'=>false,
            'yii\web\YiiAsset'=>false,
        ];

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            $this->assignForm($form->id_form, $id_row, $parentForm, $form->prefix);

            if (!Yii::$app->request->isAjax)
                $this->redirect(['/form/view','id'=>$parentForm->id_form]);
            else
                return '';
        }

        $forms = Form::find()->where(['is_template'=>1])->all();

        return $this->renderAjax('_assign_form', [
            'model'=>$form,
            'forms'=>$forms,
        ]);
    }

    /**
     * @param $id_form
     * @return string|Response
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCreateRow($id_form)
    {
        $form = $this->findModel($id_form);

        $model = new FormRow;
        $model->id_form = $id_form;
        $model->ord = FormRow::find()->where(['id_form'=>$id_form])->count();

        if ($model->save())
        {
            if (!Yii::$app->request->isAjax)
                return $this->redirect(['view', 'id' => $model->id_form]);
        }

        return '';
    }

    public function actionUpdateRow($id_row)
    {
        $model = FormRow::findOne($id_row);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if (!Yii::$app->request->isAjax)
                return $this->redirect(['form/view', 'id' => $model->id_form]);

            return $this->renderAjax('_form_row',['model' => $model]);
        }

        if (Yii::$app->request->isAjax)
        {
            Yii::$app->assetManager->bundles = [
                'yii\bootstrap\BootstrapAsset' => false,
                'yii\web\JqueryAsset'=>false,
                'yii\web\YiiAsset'=>false,
            ];

            return $this->renderAjax('_form_row',['model' => $model]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteRow($id_row)
    {
        $model = FormRow::findOne($id_row);
        $id_form = $model->id_form;

        $model->delete();

        if (!Yii::$app->request->isAjax)
            return $this->redirect(['form/view', 'id' => $id_form]);
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
     * Updates an existing Form model.
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
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        // ищем связанную коллекцию
        if ($model->id_form == $model->collection->id_form)
            $collection = $model->collection;

        if ($model->delete())
        {
            if (!empty($collection))
                $collection->delete();

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
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Form::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
