<?php

namespace backend\controllers;

use backend\models\forms\InstitutionUpdateSettingForm;
use common\jobs\InstitutionImportJob;
use common\models\District;
use Yii;
use common\models\CollectionRecord;
use common\models\collection\CollectionSearch;
use common\models\CollectionColumn;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Media;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;
/**
 * CollectionRecordController implements the CRUD actions for CollectionRecord model.
 */
class CollectionRecordController extends Controller
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
                        'actions' => ['institution-import'],
                        'roles' => ['backend.collection.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($collection = Collection::findOne(['alias' => 'institution'])) !== null) {
                                    return $collection->id_collection;
                                }
                                return null;
                            },
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['backend.collection.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['download-doc','all-doc'],
                        'roles' => ['backend.collection.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($collectionRecord = $this->findModel(Yii::$app->request->get('id'))) !== null) {
                                    return $collectionRecord->id_collection;
                                }
                                return null;
                            },
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.collection.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($collectionRecord = $this->findModel(Yii::$app->request->get('id'))) !== null) {
                                    return $collectionRecord->id_collection;
                                }
                                return null;
                            },
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.collection.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','restore'],
                        'roles' => ['backend.collection.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($collectionRecord = $this->findModel(Yii::$app->request->get('id'))) !== null) {
                                    return $collectionRecord->id_collection;
                                }
                                return null;
                            },
                            'class' => Collection::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
                        'roles' => ['backend.collection.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($collectionRecord = $this->findModel(Yii::$app->request->get('id'))) !== null) {
                                    return $collectionRecord->id_collection;
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

    /**
     * @return mixed
     * @throws Exception
     */
    public function actionInstitutionImport()
    {
        $jobId = InstitutionImportJob::getJobId();

        if (!$jobId || (!Yii::$app->queue->isWaiting($jobId) && !Yii::$app->queue->isReserved($jobId) && Yii::$app->queue->isDone($jobId))) {
            Yii::$app->session->setFlash('success', 'Запущено обновление организайций');

            $jobId = Yii::$app->queue->push(new InstitutionImportJob());

            InstitutionImportJob::saveJobId($jobId);
        } else {
            Yii::$app->session->setFlash('success', 'Обновление организайций уже выполняется');
        }

        $this->redirect(Yii::$app->request->referrer ?: '/');
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionRestore($id,$id_collection)
    {
        $model = $this->findModel($id);
        $model->data = ['is_archive'=>0];
        $model->save();

        return $this->redirect(['view', 'id' => $model->id_record,'id_collection'=>$id_collection]);
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

        if ($model->restore())
            $model->createAction(Action::ACTION_UNDELETE);

        return $this->redirect(['index', 'id' => $model->id_collection, 'archive' => 1]);
    }

    public function makeAction($model,$type,$dataProvider)
    {
        // добавляем фильтр по ID
        if ($ids = Yii::$app->request->post('ids',[]))
        {
            foreach ($ids as $key => $value)
                $ids[$key] = (int)$value;

            $dataProvider->query->andWhere(['id_record'=>$ids]);
        }

        $records = $dataProvider->query->all();

        switch ($type)
        {
            case 1: // archive and copy
                $collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);

                $archiveColumn = $model->makeArchiveColumn();

                foreach ($records as $key => $data)
                {
                    $newRecord = new CollectionRecord;
                    $newRecord->id_collection = $model->id_collection;

                    if ($newRecord->save())
                    {
                        unset($data['_id']);
                        $data['id_record'] = $newRecord->id_record;
                        $collection->insert($data);
                    }

                    $collection->update(['id_record'=>$data['id_record']],['col'.$archiveColumn->id_column=>1]);
                }

                break;
            case 2: // archive
                $collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);
                $archiveColumn = $model->makeArchiveColumn();

                foreach ($records as $key => $data)
                    $collection->update(['id_record'=>$data['id_record']],['col'.$archiveColumn->id_column=>1]);

                break;
            case 3: // copy
                $collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);

                foreach ($records as $key => $data)
                {
                    $newRecord = new CollectionRecord;
                    $newRecord->id_collection = $model->id_collection;

                    if ($newRecord->save())
                    {
                        unset($data['_id']);
                        $data['id_record'] = $newRecord->id_record;
                        $collection->insert($data);
                    }
                }
                break;
            case 4: // delete
                /*$collection = Yii::$app->mongodb->getCollection('collection'.$model->id_collection);

                foreach ($records as $key => $data)
                    $data->delete();*/

                $models = CollectionRecord::find()->where(['id_record'=>$ids])->all();

                foreach ($models as $key => $model) {
                    $model->delete();
                }

                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Lists all CollectionRecord models.
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        $settingForm = new InstitutionUpdateSettingForm();

        if ($settingForm->load(Yii::$app->request->post()))
        {
            if ($settingForm->save()) {
                Yii::$app->session->setFlash('success', 'Настройки успешно сохранены');
                $this->refresh();
            } else {
                Yii::$app->session->setFlash('error', 'Произошла ошибка при сохранении настроек');
            }
        }

        $model = $this->findCollection($id);

        $searchModel = new CollectionSearch($model);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($action = Yii::$app->request->post('action'))
            return $this->makeAction($model, $action,$dataProvider);

        return $this->render('index', [
            'settingForm' => $settingForm,
            'model' => $model,
            'searchModel'=>$searchModel,
            'columns' => $searchModel->columns,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAllDoc($id)
    {
        $model = $this->findCollection($id);

        $form = (!empty($model->parent))?$model->parent->form:$model->form;

        if (!empty($form->template))
        {
            $zip = new \ZipArchive();
            $zip_path = Yii::getAlias('@runtime').'/'.$model->id_collection.'.zip';

            if (is_file($zip_path))
                unlink($zip_path);

            if ($zip->open($zip_path,\ZIPARCHIVE::CREATE) === TRUE)
            {
                foreach ($model->getData([],true) as $key => $data)
                {
                    $export_path = $form->makeDocByData($data);

                    if (is_file($export_path))
                        $zip->addFile($export_path,$key.'.docx');
                }

                $zip->close();

                header('Content-Description: File Transfer');
                header('Content-Type: application/zip');
                header("Content-Disposition: attachment; filename*=UTF-8''".urlencode($model->name).".zip");
                header("Content-length: " . filesize($zip_path));
                header("Pragma: no-cache");
                header("Expires: 0");
                readfile($zip_path);

                unlink($zip_path);
                exit;
            }
        }

        Yii::$app->end();
    }

    public function actionDownloadDoc($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->collection->form->template))
        {
            $export_path = $model->collection->form->makeDoc($model);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');

            if (!empty($_GET['pdf']))
            {
                exec('sudo /usr/bin/unoconv -f pdf '.$export_path);
                $export_path = str_replace('.docx', '.pdf', $export_path);

                header('Content-Disposition: attachment; filename="Record_'.$id.'.pdf"');
            }
            else
                header('Content-Disposition: attachment; filename="Record_'.$id.'.docx"');

            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($export_path));

            readfile($export_path);
            unlink($export_path);
        }

        Yii::$app->end();
    }

    /**
     * Displays a single CollectionRecord model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$id_collection=null)
    {
        $model = $this->findModel($id);
        if (!empty($id_collection))
            $collection = Collection::findOne($id_collection);
        else
            $collection = $model->collection;

        return $this->render('view', [
            'model' => $model,
            'collection'=>$collection,
        ]);
    }

    /**
     * Creates a new CollectionRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new CollectionRecord();
        $model->id_collection = $id;
        $collection = $this->findCollection($id);

        $form = new FormDynamic($collection->form);

        if ($form->load(Yii::$app->request->post()))
        {
            if ($form->validate())
            {
                $prepare = $form->prepareData(true);

                if ($model = $collection->insertRecord($prepare))
                {
                    if (!Yii::$app->request->isAjax)
                        return $this->redirect(['index', 'id' => $model->id_collection]);
                    else
                    {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'success'=>true
                        ];
                    }
                }
            }
            else
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form);
            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('_form',['model'=>$model,'collection'=>$collection]);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CollectionRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->ord = time();
        $collection = $model->collection;

        $form = new FormDynamic($collection->form);

        if ($form->load(Yii::$app->request->post()))
        {
            if ($form->validate())
            {
                $prepare = $form->prepareData(true);
                $model->data = $prepare;

                if ($model->save())
                    if (!Yii::$app->request->isAjax)
                        return $this->redirect(['index', 'id' => $model->id_collection]);
                    else
                    {
                        Yii::$app->response->format = Response::FORMAT_JSON;
                        return [
                            'success'=>true
                        ];
                    }
            }
            else
            {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($form);
            }
        }

        if (Yii::$app->request->isAjax)
            return $this->renderAjax('_form',['model'=>$model,'collection'=>$collection]);

        return $this->render('update', [
            'model' => $model,
            'collection'=>$collection,
        ]);
    }

    /**
     * Deletes an existing CollectionRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $id_collection = $model->id_collection;
        $model->delete();

        return $this->redirect(['index','id'=>$id_collection]);
    }

    /**
     * Finds the CollectionRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CollectionRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CollectionRecord::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findCollection($id)
    {
        if (($model = Collection::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
