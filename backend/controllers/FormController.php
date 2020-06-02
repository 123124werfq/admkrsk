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
use backend\models\forms\FormCopy;

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
                        'actions' => ['index', 'collection'],
                        'roles' => ['backend.form.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.form.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','create-service','copy','make-doc'],
                        'roles' => ['backend.form.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create-row'],
                        'roles' => ['backend.form.createRow', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id_form'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-row', 'undelete', 'delete-row'],
                        'roles' => ['backend.form.updateRow', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id_form'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','order','assign-form'],
                        'roles' => ['backend.form.update', 'backend.entityAccess'],
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
                        'roles' => ['backend.form.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.form.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Form::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.form.log.view', 'backend.entityAccess'],
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
                        'roles' => ['backend.form.log.restore', 'backend.entityAccess'],
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
     * Lists Form models for collection.
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionCollection($id)
    {
        $collection = $this->findModelCollection($id);

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

        return $this->render('collection', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
            'collection' => $collection,
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


        var_dump(exec('sudo /usr/bin/unoconv -f pdf '.$export_path));
        die();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');

        if (!empty($_GET['pdf']))
        {
            exec('sudo /usr/bin/unoconv -f pdf '.$export_path);
            $export_path = str_replace('.docx', '.pdf', $export_path);

            header('Content-Disposition: attachment; filename="Record_'.$id_record.'.pdf"');
        }
        else
            header('Content-Disposition: attachment; filename="Record_'.$id_record.'.docx"');

        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($export_path));

        readfile($export_path);
        unlink($export_path);
    }

    /**
     * Creates a new Form model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_collection=null)
    {
        $model = new Form();
        $model->state = 1;
        $model->id_collection = $id_collection;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if (empty($model->id_collection))
            {
                $collection = new Collection;
                $collection->name = $model->name;
                $collection->id_form = $model->id_form;

                if ($collection->save())
                {
                    $model->id_collection = $collection->id_collection;
                    $model->updateAttributes(['id_collection']);
                }
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

        $formCopy = new FormCopy;

        if ($newForm = $formCopy->сopyForm($form))
            return $this->redirect(['view', 'id'=>$newForm->id_form]);
        else
            print_r($formCopy->errors);
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
            FormCopy::assignForm($form->id_form, $id_row, $parentForm, $form->prefix);

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

        $forms = [];
        $selectedForm = null;
        $formId = Yii::$app->request->get('form-id');
        /** @var Form $form */
        foreach ($records as $key => $form) {
            $formData = [
                'text' => $form->name,
                'value' => (string)$form->id_form,
            ];
            if ($formId == $form->id_form) {
                $selectedForm = $formData;
                continue;
            }
            $forms[] = $formData;
        }
        if ($selectedForm) {
            array_unshift($forms, $selectedForm);
        }

        return json_encode($forms);
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
        if (!empty($model->collection) && $model->isMainForm())
        {
            $collection = $model->collection;

            if (!empty($collection))
            {
                foreach ($collection->forms as $key => $form)
                {
                    if ($form->delete() && $model->id_form != $form->id_form)
                        $form->createAction(Action::ACTION_DELETE);
                }

                $collection->delete();
                $collection->createAction(Action::ACTION_DELETE);
            }
        }

        if ($model->delete())
            $model->createAction(Action::ACTION_DELETE);

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

    protected function findModelCollection($id)
    {
        if (($model = Collection::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}