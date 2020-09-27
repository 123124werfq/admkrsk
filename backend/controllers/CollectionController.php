<?php

namespace backend\controllers;

use backend\models\forms\CollectionConvertForm;
use common\models\Action;
use common\models\SettingPluginCollection;
use common\modules\log\models\Log;
use Throwable;
use Yii;
use common\models\Collection;
use common\models\FormInput;
use common\models\Form;
use common\models\House;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
use backend\models\search\CollectionSearch;
use backend\models\forms\CollectionRecordSearchForm;
use backend\models\forms\CollectionImportForm;
use backend\models\forms\CollectionCombineForm;
use backend\models\forms\FormCopy;

use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\mongodb\Exception;
use yii\db\Exception as DbException;
use yii\validators\NumberValidator;
use yii\web\HttpException;
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
    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;

        return parent::beforeAction($action);
    }

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
                        'roles' => ['backend.collection.import', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'redactor', 'record-map', 'partition', 'pages'],
                        'roles' => ['backend.collection.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['get-collections'],
                        'roles' => ['backend.collection.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.collection.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'record', 'record-search-redactor', 'create-view', 'copy', 'assign', 'convert-type', 'record-list'],
                        'roles' => ['backend.collection.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.collection.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete', 'delete-record'],
                        'roles' => ['backend.collection.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.collection.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.collection.log.view', 'backend.entityAccess'],
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
                        'roles' => ['backend.collection.log.restore', 'backend.entityAccess'],
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
     * @throws InvalidConfigException
     */
    public function actionList($q,$id_type=null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Collection::find();

        if (!empty($id_type))
            $query->andWhere(['id_type'=>$id_type]);

        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere([
                'or',
                ['id_collection' => $q],
                ['ilike', 'name', $q],
            ]);
        } else {
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

    /**
     * @param $id
     * @param $q
     * @param null $id_column
     * @return array
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionRecordList($id, $q='', $id_column = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $collection = $this->findModel($id);
        $query = $collection->getDataQuery();

        $i = 0;
        $results = [];

        $id_column = (int)$id_column;

        if (!empty($q))
            $query->andWhere(['like','col'.$id_column,$q]);

        foreach ($query->limit(30)->getStrinyfyArray() as $key => $value)
        {
            $results[] = [
                'id' => $key,
                'text' => $value[$id_column]??'',
            ];
        }

        return ['results' => $results];
    }

    public function actionCopy($id,$copydata = true)
    {
        $model = $this->findModel($id);

        $formCopy = new FormCopy;
        $formCopy->copydata = true;

        if ($newForm = $formCopy->сopyForm($model->form))
            return $this->redirect(['view', 'id'=>$newForm->id_collection]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionConvertType($id)
    {
        set_time_limit(0);

        $form = new CollectionConvertForm;

        $collection = $this->findModel($id);
        $columns = $collection->getColumns()->indexBy('alias')->all();;

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            switch ($form->type)
            {
                case CollectionColumn::TYPE_REPEAT:

                    $col = Yii::$app->request->post('address');

                    if (!empty($col))
                    {
                        $newColumn = $collection->createColumn([
                            'name' => 'Дата проведения',
                            'alias' => 'date_repeat',
                            'type' => CollectionColumn::TYPE_REPEAT,
                        ]);

                        if ($newColumn)
                        {
                            // добавляем инпут в форму
                            $newColumn->collection->form->createInput([
                                'type' => $newColumn->type,
                                'name' => $newColumn->name,
                                'label' => $newColumn->name,
                                'fieldname' => $newColumn->alias,
                                'id_column' => $newColumn->id_column,
                            ]);

                            $alldata = $collection->getData([], true);

                            foreach ($alldata as $id_record => $data)
                            {
                                if (empty($data[$col]))
                                    continue;

                                $repeat = new \SimpleXMLElement($data[$col]);

                                $record = CollectionRecord::findOne($id_record);

                                $begin = strtotime($repeat->minDate);
                                $end = strtotime($repeat->maxDate);

                                $insert = [
                                    "begin" => $begin,
                                    "end" => $end,
                                    "is_repeat" => 0,
                                    "repeat_count" => "",
                                    "repeat" => "",
                                    "day_space" => "",
                                    "week_space" => "",
                                    "repeat_month" => "",
                                    "month_days" => "",
                                    "week_number" => "",
                                    "month_week" => []
                                ];
                                $record->data = [$newColumn->id_column => $insert];
                                $record->update();
                            }
                        }
                        else
                            print_r($newColumn->errors);
                    }

                    break;

                case CollectionColumn::TYPE_MAP:
                    $x = Yii::$app->request->post('x');
                    $y = Yii::$app->request->post('y');

                    if (!empty($x) && !empty($y))
                    {
                        $newColumn = $collection->createColumn([
                            'name' => 'Координаты',
                            'alias' => 'map_coords',
                            'type' => CollectionColumn::TYPE_MAP,
                        ]);

                        if ($newColumn)
                        {
                            // добавляем инпут в форму
                            $newColumn->collection->form->createInput([
                                'type' => $newColumn->type,
                                'name' => $newColumn->name,
                                'label' => $newColumn->name,
                                'fieldname' => $newColumn->alias,
                                'id_column' => $newColumn->id_column,
                            ]);

                            $alldata = $collection->getData([], true);

                            foreach ($alldata as $id_record => $data)
                            {
                                $record = CollectionRecord::findOne($id_record);

                                if ($x==$y)
                                {
                                    $coords = explode(',',$data[$x]);

                                    if (count($coords)>2)
                                        $record->data = [$newColumn->id_column => [str_replace('[', '', $coords[0]), str_replace(']', '', $coords[1])]];
                                }
                                else
                                    $record->data = [$newColumn->id_column => [str_replace(',', '.', $data[$x]), str_replace(',', '.', $data[$y])]];

                                $record->update();
                            }
                        }
                        else
                            print_r($newColumn->errors);
                    }

                    break;

                case CollectionColumn::TYPE_ADDRESS:

                    $col = Yii::$app->request->post('address');
                    $x = Yii::$app->request->post('x');
                    $y = Yii::$app->request->post('y');

                    if (!empty($col))
                    {
                        $newColumn = $collection->createColumn([
                            'name' => 'Адрес',
                            'alias' => 'address',
                            'type' => CollectionColumn::TYPE_ADDRESS,
                        ]);

                        $alldata = $collection->getData([], true);

                        foreach ($alldata as $id_record => $data)
                        {
                            if (empty($data[$col]))
                                continue;

                            $address = new \SimpleXMLElement($data[$col]);

                            $empty = [
                                'country'=>'',
                                'id_country'=>'',
                                'region'=>'',
                                'id_region'=>'',
                                'subregion'=>'',
                                'id_subregion'=>'',
                                'city'=>'',
                                'id_city'=>'',
                                'district'=>'',
                                'id_district'=>'',
                                'street'=>'',
                                'id_street'=>'',
                                'house'=>'',
                                'id_house'=>'',
                                'room'=>'',
                                'fullname'=>'',
                                'houseguid'=>'',
                                'lat'=>'',
                                'lon'=>'',
                                'postalcode'=>'',
                            ];

                            foreach ($address->level as $key => $level) {
                                $attrs = $level->attributes();
                                $id = (int)$attrs->id;

                                $level = (string)$level[0];

                                switch ($id) {
                                    case 0:
                                        $empty['country'] = $level;
                                        break;
                                    case 1:
                                        $empty['region'] = $level;
                                        break;
                                    case 2:
                                        $empty['subregion'] = $level;
                                        break;
                                    case 3:
                                        $level = str_replace('Красноярск г', 'г Красноярск', $level);
                                        $empty['city'] = $level;
                                        break;
                                    case 5:
                                        $empty['district'] = $level;
                                        break;
                                    case 7:
                                        $empty['street'] = $level;
                                        break;
                                    case 8:
                                        $empty['house'] = $level;
                                        break;
                                    case 11:
                                        $empty['room'] = (string)($attrs->type).$level;
                                        break;
                                    case 12:
                                        $empty['postalcode'] = $level;
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }

                            $empty = House::fillID($empty);

                            $empty['fullname'] = implode(', ', array_filter($empty));

                            if (!empty($x) && !empty($y) && !empty($data[$x]))
                            {
                                $empty['lat'] = str_replace(',', '.', $data[$x]);
                                $empty['lon'] = str_replace(',', '.', $data[$y]);
                            }

                            $record = CollectionRecord::findOne($id_record);
                            $record->data = [$newColumn->id_column => $empty];
                            $record->update();
                        }

                        // добавляем инпут в форму
                        $newColumn->collection->form->createInput([
                            'type' => $newColumn->type,
                            'name' => $newColumn->name,
                            'label' => $newColumn->name,
                            'fieldname' => $newColumn->alias,
                            'id_column' => $newColumn->id_column,
                        ]);

                    }

                    break;

                case CollectionColumn::TYPE_CHECKBOXES:

                    $col = Yii::$app->request->post('column');

                    if (!empty($col))
                    {
                        $newColumn = $collection->createColumn([
                            'name' => 'Координаты',
                            'alias' => 'map_coords',
                            'type' => CollectionColumn::TYPE_MAP,
                        ]);

                        if ($newColumn)
                        {
                            // добавляем инпут в форму
                            $newColumn->collection->form->createInput([
                                'type' => $newColumn->type,
                                'name' => $newColumn->name,
                                'label' => $newColumn->name,
                                'fieldname' => $newColumn->alias,
                                'id_column' => $newColumn->id_column,
                            ]);

                            $alldata = $collection->getData([], true);

                            foreach ($alldata as $id_record => $data)
                            {
                                $record = CollectionRecord::findOne($id_record);
                                $record->data = [$newColumn->id_column => [$data[$x], $data[$y]]];
                                $record->update();
                            }
                        }
                        else
                            print_r($newColumn->errors);
                    }

                    break;

                default:
                    # code...
                    break;
            }

            return $this->refresh();
        }

        return $this->render('convert/convert',[
            'formConvert'=>$form,
            'columns'=>$columns,
            'model'=>$collection,
        ]);
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws DbException
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function actionAssign($id)
    {
        set_time_limit(0);
        $collection = $this->findModel($id);

        $form = new CollectionCombineForm;
        $form->id_collection = $collection->id_collection;

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $newColumn = new CollectionColumn;
            $newColumn->name = $form->column_name;
            $newColumn->alias = $form->alias;
            $newColumn->id_collection = $id;
            $newColumn->type = $form->type;

            if ($newColumn->save())
            {
                $newColumn->collection->form->createInput([
                    'type' => $form->type,
                    'name' => $newColumn->name,
                    'label' => $newColumn->name,
                    'fieldname' => $newColumn->alias,
                    'id_column' => $newColumn->id_column,
                    'id_collection_column' => $form->id_collection_from_column_label,
                    'id_collection' => $form->id_collection_from,
                ]);

                if ($form->type == CollectionColumn::TYPE_COLLECTIONS) {
                    $datas = $collection->getData();
                    $datas_source = Collection::findOne($form->id_collection_from)->getData();

                    $collection = Yii::$app->mongodb->getCollection('collection' . $id);

                    foreach ($datas as $id_record => $data)
                    {
                        if (!empty($data[$form->id_collection_column]))
                        {
                            $array = $data[$form->id_collection_column];

                            $array = str_replace(['[', ']', ", ", '""'], ['{', '}', ":", '\"'], $array);
                            $array = str_replace(['{{', '}}', '}:{'], ['[{', '}]', '},{'], $array);
                            $array = json_decode($array, true);

                            if (!empty($array))
                            {
                                $id_records_source = [];
                                $textSearch = [];

                                foreach ($array as $akey => $kdata)
                                {
                                    $id = key($kdata);

                                    foreach ($datas_source as $id_record_source => $sources)
                                    {
                                        if ($sources[$form->id_collection_from_column] == $id)
                                        {
                                            $id_records_source[] = $id_record_source;
                                            $textSearch[$id_record_source] = $kdata[$id];
                                        }
                                    }
                                }

                                if (!empty($id_records_source))
                                {
                                    Yii::$app->db->createCommand()->insert('db_collection_value',[
                                        'id_record' => $id_record,
                                        'id_column' => $newColumn->id_column,
                                        'value' => json_encode($id_records_source)
                                    ])->execute();

                                    $update = [];
                                    $update['col' . $newColumn->id_column] = $id_records_source;
                                    $update['col' . $newColumn->id_column . '_search'] = json_encode($textSearch,JSON_UNESCAPED_UNICODE);

                                    $collection->update(['id_record' => $id_record], $update);
                                }
                            }
                        }
                    }
                }
                else
                {
                    $datas = $collection->getData();
                    $datas_source = Collection::findOne($form->id_collection_from)->getData();

                    $collection = Yii::$app->mongodb->getCollection('collection' . $id);

                    foreach ($datas as $id_record => $data)
                    {
                        if (!empty($data[$form->id_collection_column])) {
                            $id_link = $data[$form->id_collection_column];
                            $textSearch = $id_source = null;

                            if (strpos($id_link, '[[')!==false)
                            {
                                $id_link = str_replace(['[', ']', ", ", '""'], ['{', '}', ":", '\"'], $id_link);
                                $id_link = str_replace(['{{', '}}', '}:{'], ['[{', '}]', '},{'], $id_link);
                                $id_link = json_decode($id_link, true);

                                if (!empty($id_link[0]))
                                    $id_link = key($id_link[0]);
                            }

                            foreach ($datas_source as $id_record_source => $sources) {
                                if ($sources[$form->id_collection_from_column] == $id_link) {
                                    $id_source = $id_record_source;
                                    $textSearch = $sources[$form->id_collection_from_column_label];
                                }
                            }

                            if (!empty($id_source))
                            {
                                Yii::$app->db->createCommand()->insert('db_collection_value', [
                                    'id_record' => $id_record,
                                    'id_column' => $newColumn->id_column,
                                    'value' => json_encode([$id_source])
                                ])->execute();

                                $update = [];
                                $update['col' . $newColumn->id_column] = [$id_source];
                                $update['col' . $newColumn->id_column . '_search'] = $textSearch;
                                $collection->update(['id_record' => $id_record], $update);
                            }
                        }
                    }
                }

                return $this->redirect(['view', 'id' => $form->id_collection]);
            }
        }

        return $this->render('assign', [
            'formAssign' => $form,
            'model' => $collection,
        ]);
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

    public function actionGetCollections($map=null)
    {
        $collections = Collection::find()->where('is_dictionary IS NULL');

        if (!empty($map))
            $collections->andWhere('id_column_map IS NOT NULL');

        $collections = $collections->all();

        $output = [];
        foreach ($collections as $key => $data) {
            $output[] = ['text' => $data->name, 'value' => (string)$data->id_collection];
        }

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

    public function actionPages($id)
    {
        $collection = $this->findModel($id);

        if (empty($collection))
            throw new NotFoundHttpException('The requested page does not exist.');

        $dataProvider = new ActiveDataProvider([
            'query' => $collection->getPages(),
            'sort' => [
                'defaultOrder' => [
                    'ord' => SORT_ASC
                ]
            ],
            'pagination' => [
                'pageSize' => 9000,
            ]
        ]);

        return $this->render('pages', [
            'dataProvider' => $dataProvider,
            'collection'=>$collection,
        ]);
    }

    /**
     * Displays a single Collection model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->redirect(['collection-record/index', 'id' => $id]);
    }

    /**
     * @param $id
     * @param null $id_record
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionRecord($id, $id_record = null)
    {
        $collection = $this->findModel($id);

        if (!empty($id_record)) {
            $collectionRecord = CollectionRecord::findOne($id_record);
        } elseif (!empty(Yii::$app->request->post('CollectionRecord.id_record'))) {
            $collectionRecord = CollectionRecord::findOne(Yii::$app->request->post('CollectionRecord.id_record'));
        } else {
            $collectionRecord = new CollectionRecord;
            $collectionRecord->id_collection = $id;
            $collectionRecord->ord = CollectionRecord::find()->where(['id_collection' => $id])->count();
        }

        if (!empty(Yii::$app->request->post('CollectionRecord'))) {
            $collectionRecord->data = Yii::$app->request->post('CollectionRecord');

            if ($collectionRecord->save()) {

            }

            return $this->redirect("/collection/view?id=$id");
        }

        return $this->renderPartial('_form_record', [
            'collection' => $collection,
            'model' => $collectionRecord,
            'data' => $collectionRecord->getData()
        ]);
    }

    public function actionRecordSearchRedactor()
    {
        $model = new CollectionRecordSearchForm;

        if ($model->load(Yii::$app->request->post()))
        {
            if (!empty(Yii::$app->request->post('ViewColumns')))
            {
                if (!empty(Yii::$app->request->post('ViewColumns'))) {
                    $options['columns'] = [];

                    foreach (Yii::$app->request->post('ViewColumns') as $key => $data) {
                        $options['columns'][] = [
                            'id_column' => $data['id_column'],
                            'show_for_searchcolumn'=> $data['show_for_searchcolumn']??'',
                            'class'=> $data['class']??'',
                            'filelink'=> $data['filelink']??'',
                            'group'=> $data['group']??'',
                        ];
                    }
                }

                if (!empty(Yii::$app->request->post('SearchColumns'))) {
                    $options['search'] = [];

                    foreach (Yii::$app->request->post('SearchColumns') as $key => $data) {
                        $options['search'][] = [
                            'id_column' => $data['id_column'],
                            'type' => isset($data['type']) ? $data['type'] : '0',
                        ];
                    }
                }

                if (!empty($model->filters))
                    $options['filters'] = $model->filters;
                else
                    $options['filters'] = '';

                $options['id_collection'] = $model->id_collection;

                return json_encode([
                    'id_collection'=>$model->id_collection,
                    'attributes'=>base64_encode(json_encode($options))
                ]);
            }
        }

        Yii::$app->assetManager->bundles = [
            'yii\bootstrap\BootstrapAsset' => false,
            'yii\web\JqueryAsset'=>false,
            'yii\web\YiiAsset'=>false,
        ];

        return $this->renderAjax('search_record_redactor', [
            'model' => $model,
        ]);
    }

    public function actionRecordMap($id_collection)
    {
        $model = new \backend\models\forms\CollectionRecordForm;
        $collection = $this->findModel($id);

        return $this->render('record_map',[
            'collection'=>$collection,
            'model'=>$modelm
        ]);
    }

    /**
     * @return array|string
     * @throws HttpException
     */
    public function actionRedactor($id_type=null)
    {
        $this->layout = 'clear';
        $model = new Collection;
        //$model->id_parent_collection = 52;
        $model->name = 'temp';
        $requestParams = array_merge(Yii::$app->request->get(), Yii::$app->request->post());

        /** configure and return changes of collection */
        if (isset($requestParams['configureEditCollection']))
        {
            /** update plugin options */
            $model->mapPropsAndAttributes(Yii::$app->request->post('Collection'));
            $model->isEdit = true;

            $updatePluginOptions = $this->configureJsonCollection($model);
            $jsonFormatOptions = base64_decode($updatePluginOptions['base64']);
            $updatePluginOptions['key'] = $model->updatePluginSettings($requestParams['key'], $jsonFormatOptions);

            return json_encode($updatePluginOptions);
        }

        /** open modal dialog for edit collection */
        if (isset($requestParams['edit']) && isset($requestParams['key']))
        {
            $pluginOptions = SettingPluginCollection::getSettings($requestParams['key']);
            if (!$pluginOptions) {
                throw new HttpException(400);
            }
            /** decode collection options */
            $decodePluginOptions = json_decode($pluginOptions->settings,true);
            $model->mapPropsAndAttributes($decodePluginOptions);
            $model->isEdit = true;
        }

        /** configure and return new data collection */
        if ($model->load($requestParams) && !empty(Yii::$app->request->post('json')))
        {
            $model->mapPropsAndAttributes($requestParams['Collection']);
            $configureJsonPluginOptions = $this->configureJsonCollection($model);
            /** save plugin options */
            $jsonFormatOptions = base64_decode($configureJsonPluginOptions['base64']);
            $configureJsonPluginOptions['key'] = $model->savePluginSettings($jsonFormatOptions);

            return json_encode($configureJsonPluginOptions);
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('redactor', [
                'model' => $model,
                'id_type' => $id_type,
            ]);

        return $this->render('redactor', [
            'model' => $model,
            'id_type' => $id_type,
        ]);
    }

    /**
     * @param Collection $model
     * @return array
     */
    private function configureJsonCollection($model)
    {
        $collectionPluginSettings = $this->saveView($model, true);

        $collectionPluginSettings['id_collection'] = $model->id_parent_collection;
        $collectionPluginSettings['template_view'] = $model->template_view;
        $collectionPluginSettings['id_group'] = $model->id_group;
        $collectionPluginSettings['link_column'] = $model->link_column;
        $collectionPluginSettings['id_column_order'] = $model->id_column_order;
        $collectionPluginSettings['order_direction'] = $model->order_direction;
        $collectionPluginSettings['pagesize'] = $model->pagesize;
        $collectionPluginSettings['table_head'] = $model->table_head;
        $collectionPluginSettings['table_style'] = $model->table_style;
        $collectionPluginSettings['download_columns'] = $model->download_columns;
        $collectionPluginSettings['show_download'] = $model->show_download;
        $collectionPluginSettings['show_row_num'] = $model->show_row_num;
        $collectionPluginSettings['show_on_map'] = $model->show_on_map;
        $collectionPluginSettings['show_column_num'] = $model->show_column_num;
        $collectionPluginSettings['base64'] = base64_encode(json_encode($collectionPluginSettings));
        $collectionPluginSettings['isEdit'] = $model->isEdit;
        return $collectionPluginSettings;
    }


    /**
     * Creates a new Collection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \Exception
     */
    public function actionCreate()
    {
        $model = new Collection();
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if (!empty($model->id_type))
            {
                foreach ($model->type->columns as $key => $data)
                {
                    $column = new CollectionColumn;
                    $column->id_collection = $model->id_collection;
                    $column->name = $data->name;
                    $column->alias = $data->alias;
                    $column->type = $data->type;
                    if (!$column->save())
                        print_r($column->errors);
                }
            }

            $model->createForm();
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

            $form = new Form();
            $form->state = 1;
            $form->name = $model->name;
            $form->id_collection = $model->id_collection;

            if ($form->save())
            {
                $model->id_form = $form->id_form;
                $model->updateAttributes(['id_form']);
            }

            return $this->redirect(['view', 'id' => $model->id_collection]);
        }

        return $this->render('create_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param Collection $model
     * @return string|Response
     */
    public function actionUpdateView($model)
    {
        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $this->saveView($model);

            return $this->redirect(['view', 'id' => $model->id_collection]);
        }

        return $this->render('update_view', [
            'model' => $model,
        ]);
    }

    /**
     * @param Collection $model
     * @param bool $return
     * @return array
     */
    protected function saveView($model, $return = false)
    {
        $options = [];

        if (!empty(Yii::$app->request->post('ViewColumns'))) {
            $options['columns'] = [];

            foreach (Yii::$app->request->post('ViewColumns') as $key => $data) {
                $options['columns'][] = [
                    'id_column' => $data['id_column'],
                    'show_for_searchcolumn'=> $data['show_for_searchcolumn']??'',
                    'class'=> $data['class']??'',
                    'filelink'=> $data['filelink']??'',
                    'group'=> $data['group']??'',
                ];
            }
        }

        if (!empty(Yii::$app->request->post('SearchColumns'))) {
            $options['search'] = [];

            foreach (Yii::$app->request->post('SearchColumns') as $key => $data) {
                $options['search'][] = [
                    'id_column' => $data['id_column'],
                    'type' => isset($data['type']) ? $data['type'] : '0',
                ];
            }
        }

        if (!empty($model->filters))
            $options['filters'] = $model->filters;
        else
            $options['filters'] = '';

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
     * @throws InvalidConfigException
     */
    public function actionUpdate($id)
    {
        /** @var Collection $model */
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

    public function actionImport($id=null)
    {
        set_time_limit(0);

        $model = new CollectionImportForm;
        $model->load(Yii::$app->request->post());
        $model->file = UploadedFile::getInstance($model, 'file');

        $existCollection = null;

        if (!empty($id))
        {
            $existCollection = $this->findModel($id);
            $model->name = $existCollection->name;
        }

        if (!empty($model->file) || !empty($model->filepath))
        {
            if (empty($model->filepath))
            {
                $root = Yii::getAlias('@app');
                $model->filepath = $root.'/runtime/import_'.time().'.'.$model->file->extension;
                $model->file->saveAs($model->filepath);
            }

            if (is_file($model->filepath))
                $data = \moonland\phpexcel\Excel::import($model->filepath, [
                    'setFirstRecordAsKeys' => false,
                    'setIndexSheetByName' => true,
                ]);
            else
                $model->addError('file','Файл не найден');

            if (!empty($data))
            {
                if (isset($data[1]['A']))
                    $data = ['Страница 1'=>$data];

                if (!empty($model->sheet))
                {
                    $columns = [];
                    $keys = [];
                    $records = [];

                    //if (empty($_POST['import']))
                    //{
                    $sheet_pos = array_search($model->sheet, array_keys($data));

                    if (!empty($_POST['CollectionImportForm'][$sheet_pos]))
                    {
                        $post = $_POST['CollectionImportForm'][$sheet_pos];
                        $model->load($post);

                        $model->skip = (int)$post['skip'];
                        $model->keyrow = (int)$post['keyrow'];
                        $model->firstRowAsName = $post['firstRowAsName'];
                    }
                    else
                    {
                        //$post = Yii::$app->request->post("CollectionImportForm.$sheet_pos");
                        //$model->load($post);
                        $model->load(Yii::$app->request->post());
                    }

                    //}

                    foreach ($data[$model->sheet] as $rowkey => $row)
                    {
                        // устанавливаем алиасы
                        if ($rowkey==$model->keyrow && $model->keyrow>0)
                        {
                            if (empty($columns))
                                $columns = $row;

                            $keys = $row;
                        }

                        // пропускаем
                        if (!empty($model->skip) && $rowkey <= $model->skip)
                            continue;

                        // устанавливаем именя колонок по первой строке если выбрали
                        if ($rowkey == ((int)$model->skip + 1) && $model->firstRowAsName)
                        {
                            $columns = $row;
                            continue;
                        }

                        $records[] = $row;
                    }

                    if (!empty($_POST['import']))
                    {
                        try {

                            if (!empty($records))
                            {
                                if (!empty($existCollection))
                                {
                                    $collection = $existCollection;

                                    if ($model->erase)
                                    {
                                        foreach ($collection->items as $rkey => $record)
                                            $record->delete();
                                    }
                                }
                                else
                                {
                                    $collection = new Collection();
                                    $collection->name = $model->name;
                                }

                                if ($collection->save())
                                {

                                    $columns = [];

                                    $i = 0;

                                    foreach ($model->columns as $tdkey => $column)
                                    {
                                        $columnModel = null;

                                        if (!empty($column['id_column']))
                                        {
                                            if ($column['id_column']<0)
                                                continue;

                                            $columnModel = CollectionColumn::find()->where(['id_column'=>$column['id_column']])->one();
                                        }

                                        if (empty($columnModel))
                                        {
                                            $columnModel = new CollectionColumn;
                                            $columnModel->name = $column['name'];
                                            $columnModel->type = $column['type'];
                                            $columnModel->alias = strtolower(\common\components\helper\Helper::transFileName($column['alias']));
                                            $columnModel->ord = $i;
                                            $columnModel->id_collection = $collection->id_collection;
                                        }

                                        if ($columnModel->save())
                                            $columns[$tdkey] = $columnModel;
                                        else
                                        {
                                            print_r($columnModel->errors);
                                            die();
                                        }

                                        $i++;
                                    }

                                    $values = [];
                                    foreach ($records as $rkey => $row)
                                    {
                                        $collectionRecord = new CollectionRecord;
                                        $collectionRecord->id_collection = $collection->id_collection;
                                        $collectionRecord->ord = $rkey;

                                        $insert = [];

                                        foreach ($row as $tdkey => $value)
                                        {
                                            if (!isset($columns[$tdkey]))
                                                continue;

                                            $value = str_replace('\n', "\r\n", $value);

                                            switch ($columns[$tdkey]->type)
                                            {
                                                case CollectionColumn::TYPE_INTEGER:
                                                    $insert[$columns[$tdkey]->id_column] = str_replace(',', '.', $value);
                                                    break;
                                                case CollectionColumn::TYPE_DATE:
                                                case CollectionColumn::TYPE_DATETIME:
                                                    $insert[$columns[$tdkey]->id_column] = strtotime($value);
                                                    break;
                                                case CollectionColumn::TYPE_SELECT:
                                                    $insert[$columns[$tdkey]->id_column] = $value;
                                                    if (!empty($value))
                                                    $values[$columns[$tdkey]->id_column][$value] = $value;
                                                    break;
                                                default:
                                                    $insert[$columns[$tdkey]->id_column] = $value;
                                                    break;
                                            }
                                        }

                                        $collectionRecord->data = $insert;

                                        if (!$collectionRecord->save())
                                            print_r($collectionRecord->errors);

                                    }

                                    Yii::$app->session->setFlash('success', 'Данные импортированы');

                                    $collection->createForm();

                                    foreach ($values as $id_column => $value)
                                    {
                                        $input = FormInput::find()->where(['id_column'=>$id_column])->one();

                                        if (!empty($input))
                                            $input->values = json_encode($values);

                                        $input->updateAttributes(['values']);
                                    }

                                    @unlink($model->filepath);

                                    return $this->redirect(['view', 'id' => $collection->id_collection]);
                                }
                                else
                                    print_r($collection->errors);
                            }
                            else
                            {
                                Yii::$app->session->setFlash('error', 'Нет данных для записи');
                                return $this->refresh();
                            }
                        }
                        catch (Exception $e)
                        {
                            Yii::$app->session->setFlash('error', 'Ошибка при записи в базу данных');
                            $model->addError('file','Ошибка при чтении файла, ошибка формата данных');
                        }
                    }
                    else
                    {
                        // сохраняем названия колонок
                        if (empty($columns))
                        {
                            $i = 0;
                            foreach ($records[1] as $rkey => $value)
                                $columns[$rkey] = 'Колонка №'.($i++);
                        }

                        if (empty($keys))
                            foreach ($columns as $key => $column)
                                $keys[$key] = strtolower(\common\components\helper\Helper::transFileName($column));

                        return $this->render('import/import_column',[
                            'model'=>$model,
                            'records'=>$records,
                            'columns'=>$columns,
                            'existCollection'=>$existCollection,
                            'keys'=>$keys,
                        ]);
                    }
                }
                else
                    return $this->render('import/import',[
                        'model'=>$model,
                        'table'=>$data
                    ]);
            }
        }

        return $this->render('import/import', [
            'model' => $model,
            'id'=>$id,
        ]);
    }


    public function actionDeleteRecord($id)
    {
        $model = CollectionRecord::findOne($id);

        $id_collection = $model->id_collection;
        $model->delete();

        return $this->redirect(['view', 'id' => $id_collection]);
    }

    /**
     * Deletes an existing Collection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete())
        {
            foreach ($model->forms as $key => $form)
                if ($form->delete())
                        $form->createAction(Action::ACTION_DELETE);

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
     * Finds the Collection model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Collection the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Collection::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}