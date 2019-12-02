<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\Collection;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
use common\models\Form;
use backend\models\search\CollectionSearch;
use backend\models\CollectionImportForm;
use yii\helpers\ArrayHelper;
use yii\validators\NumberValidator;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CollectionController implements the CRUD actions for Collection model.
 */
class CollectionController extends Controller
{
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
                        'actions' => ['list'],
                        'roles' => ['backend.collection.list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['columns'],
                        'roles' => ['backend.collection.list'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['import'],
                        'roles' => ['backend.collection.import'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','redactor'],
                        'roles' => ['backend.collection.index'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['get-collections'],
                        'roles' => ['backend.collection.index'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.collection.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create','record', 'create-view','copy'],
                        'roles' => ['backend.collection.create'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.collection.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete','delete-record'],
                        'roles' => ['backend.collection.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.collection.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.collection.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.collection.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Collection::class,
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

    public function actions()
    {
        return [
            'history' => [
                'class' => 'backend\modules\log\actions\IndexAction',
                'modelClass' => Collection::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Collection::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Collection::class,
            ],
        ];
    }

    /**
     * Search Collection models.
     * @param string $q
     * @return mixed
     */
    public function actionList($q)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Collection::find();

        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere([
                'or',
                ['id_collection' => $q],
                ['ilike', 'name', $q],
            ]);
        }
        else
        {
            $query->andWhere(['ilike', 'name', $q]);
        }

        $results = [];
        foreach ($query->limit(10)->all() as $collection) {
            /* @var Collection $collection */
            $results[] = [
                'id' => $collection->id_collection,
                'text' => $collection->name,
            ];
        }

        return ['results' => $results];
    }

    public function actionCopy($id)
    {
        $model = $this->findModel($id);

        $newCollection = new Collection;
        $newCollection->attributes = $model->attributes;
        $newCollection->id_form = null;
        $newCollection->name = 'Копия '.$model->name;

        if ($newCollection->save())
        {
            $compare = [];

            foreach ($model->columns as $key => $column)
            {
                $newCol = new CollectionColumn;
                $newCol->attributes = $column->attributes;
                $newCol->id_collection = $newCollection->id_collection;

                if ($newCol->save())
                    $compare[$column->id_column] = $newCol->id_column;
            }

            $records = $model->getData();

            foreach ($records as $rkey => $row)
            {
                $insert = [];

                foreach ($row as $oldcol => $value)
                    if (isset($compare[$oldcol]))
                        $insert[$compare[$oldcol]] = $value;

                $newRecord = new CollectionRecord;
                $newRecord->id_collection = $newCollection->id_collection;
                $newRecord->data = $insert;
                $newRecord->save();
            }

            $this->redirect(["collection/view",'id'=>$newCollection->id_collection]);
        }
        else print_r($newCollection->errors);
    }

    /**
     * Search User models.
     * @param integer $id
     * @return mixed
     */
    public function actionColumns($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $results = [];

        if (($collection = Collection::findOne($id)) !== null) {
            $results = ArrayHelper::map($collection->columns, 'id_column', 'name');
        }

        return ['results' => $results];
    }

    public function actionGetCollections()
    {
        $collections = Collection::find()->where('is_dictionary IS NULL')->all();

        $output = [];
        foreach ($collections as $key => $data)
            $output[] = ['text'=>$data->name,'value'=>(string)$data->id_collection];

        return json_encode($output);
    }

    /**
     * Lists all Collection models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CollectionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->redirect(['collection-record/index','id'=>$id]);
    }

    public function actionRecord($id,$id_record=null)
    {
        $collection = $this->findModel($id);

        if (!empty($id_record))
            $collectionRecord = CollectionRecord::findOne($id_record);
        elseif (!empty($_POST['CollectionRecord']['id_record']))
            $collectionRecord = CollectionRecord::findOne($_POST['CollectionRecord']['id_record']);
        else
        {
            $collectionRecord = new CollectionRecord;
            $collectionRecord->id_collection = $id;
            $collectionRecord->ord = CollectionRecord::find()->where(['id_collection'=>$id])->count();
        }

        if (!empty($_POST['CollectionRecord']))
        {
            $collectionRecord->data = $_POST['CollectionRecord'];

            if ($collectionRecord->save())
            {

            }

            return $this->redirect("/collection/view?id=$id");
        }

        return $this->renderPartial('_form_record',[
            'collection'=>$collection,
            'model'=>$collectionRecord,
            'data'=>$collectionRecord->getData()
        ]);
    }

    public function actionRedactor()
    {
        $this->layout = 'clear';

        $model = new Collection();
        $model->name = 'temp';
        //$model->id_parent_collection = Yii::$app->request->post('id_collection');

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            if (!empty($_POST['json']))
            {
                $json = $this->saveView($model,true);

                $json['id_collection'] = $model->id_parent_collection;
                $json['template'] = $model->template_view;

                return json_encode($json);
            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('redactor',[
                'model' => $model,
            ]);

        return $this->render('redactor',[
            'model' => $model,
        ]);
    }


    /**
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Collection();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $form = new Form;
            $form->id_collection = $model->id_collection;
            $form->name = 'Форма к колекции '.$model->id_collection;

            if ($form->save())
            {
                $form->createFromByCollection();
                $model->id_form = $form->id_form;
                $model->updateAttributes(['id_form']);
            }

            $model->createAction(Action::ACTION_CREATE);

            return $this->redirect(['form/view', 'id' => $model->id_form]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCreateView($id)
    {
        $model = new Collection();
        $model->id_parent_collection = $id;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->saveView($model);

            return $this->redirect(['update', 'id' => $model->id_collection]);
        }

        return $this->render('create_view', [
            'model' => $model,
        ]);
    }

    public function actionUpdateView($model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->saveView($model);

            return $this->redirect(['update', 'id' => $model->id_collection]);
        }

        return $this->render('update_view', [
            'model' => $model,
        ]);
    }

    protected function saveView($model,$return=false)
    {
        $options = [];

        if (!empty($_POST['ViewColumns']))
        {
            $options['columns'] = [];

            foreach ($_POST['ViewColumns'] as $key => $data)
            {
                $options['columns'][] = [
                    'id_column' => $data['id_column'],
                    'value' => (!empty($data['value']))?$data['value']:''
                ];
            }
        }

        if (!empty($_POST['ViewFilters']))
        {
            $options['filters'] = [];

            foreach ($_POST['ViewFilters'] as $key => $data)
            {
                if (!empty($data['id_column']))
                    $options['filters'][] = [
                        'id_column' => $data['id_column'],
                        'operator' => $data['operator'],
                        'value' => (!empty($data['value']))?$data['value']:''
                    ];
            }
        }

        if ($return)
            return $options;

        $model->options = json_encode($options);
        $model->updateAttributes(['options']);
    }

    /**
     * Updates an existing Collection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->id_parent_collection))
            return $this->actionUpdateView($model);

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $model->createAction(Action::ACTION_UPDATE);

            return $this->redirect(['view', 'id' => $model->id_collection]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionImport()
    {
        set_time_limit(0);

        $model = new CollectionImportForm;
        $model->load(Yii::$app->request->post());
        $model->file = UploadedFile::getInstance($model, 'file');

        if (!empty($model->file) || !empty($model->filepath))
        {
            if (empty($model->filepath))
            {
                $model->filepath = '../../temp/import_test.'.$model->file->extension;
                $model->file->saveAs($model->filepath);
            }

            try {
                $data = \moonland\phpexcel\Excel::import($model->filepath, [
                    'setFirstRecordAsKeys' => false, // if you want to set the keys of record column with first record, if it not set, the header with use the alphabet column on excel.
                    'setIndexSheetByName' => true, // set this if your excel data with multiple worksheet, the index of array will be set with the sheet name. If this not set, the index will use numeric.
                    //'getOnlySheet' => 'Реестр собствеников', // you can set this property if you want to get the specified sheet from the excel data with multiple worksheet.
                ]);

                if (!empty($data))
                {
                    if (isset($data[1]['A']))
                        $data = ['Страница 1'=>$data];

                    if (!empty($model->sheet))
                    {
                        $columns = [];
                        $records = [];

                        $sheet_pos = array_search($model->sheet, array_keys($data));

                        $post = $_POST['CollectionImportForm'][$sheet_pos];

                        $model->load($_POST['CollectionImportForm'][$sheet_pos]);

                        $model->skip = $post['skip'];
                        $model->firstRowAsName = $post['firstRowAsName'];

                        foreach ($data[$model->sheet] as $rowkey => $row)
                        {
                            if (!empty($model->skip) && $rowkey<=$model->skip)
                                continue;

                            if ($rowkey==($model->skip+1) && $model->firstRowAsName)
                            {
                                $columns = $row;
                                continue;
                            }

                            $records[] = $row;
                        }

                        if (!empty($records))
                        {
                            $collection = new Collection();
                            $collection->name = $model->name;

                            if ($collection->save())
                            {
                                // сохраняем названия колонок
                                if (empty($columns))
                                {
                                    $i = 1;
                                    foreach ($records[1] as $rkey => $value)
                                        $columns[$rkey] = 'Колонка №'.($i++);
                                }

                                foreach ($columns as $tdkey => $value)
                                {
                                    $column = new CollectionColumn;
                                    $column->name = (!empty($value))?$value:'Колонка '.$tdkey;
                                    $column->type = CollectionColumn::TYPE_INPUT;
                                    $column->alias = \common\components\helper\Helper::transFileName($column->name);
                                    $column->id_collection = $collection->id_collection;

                                    if ($column->save())
                                        $columns[$tdkey] = $column;
                                }

                                foreach ($records as $rkey => $row)
                                {
                                    $collectionRecord = new CollectionRecord;
                                    $collectionRecord->id_collection = $collection->id_collection;
                                    $collectionRecord->ord = $rkey;

                                    $insert = [];

                                    foreach ($row as $tdkey => $value)
                                        $insert[$columns[$tdkey]->id_column] = $value;

                                    $collectionRecord->data = $insert;
                                    $collectionRecord->save();
                                }

                                Yii::$app->session->setFlash('success', 'Данные импортированы');

                                unlink($model->filepath);

                                return $this->redirect(['view', 'id' => $collection->id_collection]);
                            }
                            else
                                print_r($collection->errors);
                        }
                        else
                        {
                            Yii::$app->session->setFlash('error', 'Нет данных для записи');
                            $this->refresh();
                        }
                    }
                    else
                        return $this->render('import',[
                            'model'=>$model,
                            'table'=>$data
                        ]);
                }
            }
            catch (Exception $e)
            {
                $model->addError('file','Ошибка при чтении файла, ошибка формата данных');
            }
        }

        return $this->render('import',[
            'model'=>$model,
        ]);
    }


    public function actionDeleteRecord($id)
    {
        $model = CollectionRecord::findOne($id);

        $id_collection = $model->id_collection;
        $model->delete();

        return $this->redirect(['view','id'=>$id_collection]);
    }

    /**
     * Deletes an existing Collection model.
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
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
