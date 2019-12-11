<?php

namespace backend\controllers;

use Yii;
use common\models\CollectionRecord;
use common\models\CollectionColumn;
use common\models\Collection;
use common\models\FormDynamic;
use common\models\Media;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CollectionRecord models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $model = $this->findCollection($id);

        $query = $model->getDataQuery();
        /*$query = new \yii\mongodb\Query;
        $query->from('collection'.$id);*/

        $columns = $model->getColumns()->all();

        $dataProviderColumns = [
            ['attribute'=>'id_record','label'=>'#'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<span class="btn btn-default update-record">{update}</span> <span class="btn btn-default">{delete}</span>',
                'contentOptions'=>['class'=>'button-column'],
                'urlCreator' => function ($action, $model, $key, $index) use ($id)
                {
                    if ($action === 'update') {
                        $url ='update?id='.$model['id_record'];
                        return $url;
                    }
                    if ($action === 'delete') {
                        $url ='delete?id='.$model['id_record'];
                        return $url;
                    }
                }
            ],
        ];

        $sortAttributes = ['id_record'];
        foreach ($columns as $key => $col)
        {
            $col_alias = 'col'.$col->id_column;

            $options = [];

            if (!empty($col->options['width']))
                $options['width'] = $col->options['width'].'px';

            $dataProviderColumns[$col_alias] = [
                'label'=>$col->name,
                'attribute'=>$col_alias,
                'format' => 'text',
                'headerOptions'=>$options,
            ];

            if ($col->type==CollectionColumn::TYPE_INTEGER)
                $dataProviderColumns[$col_alias]['format'] = 'integer';

            if ($col->type==CollectionColumn::TYPE_DATE)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y'];

            if ($col->type==CollectionColumn::TYPE_DISTRICT)
            {
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {
                    if (empty($model[$col_alias]))
                        return '';

                    $district = \common\models\District::findOne($model[$col_alias]);

                    if (!empty($district))
                        return $district->name;

                    return '';
                };
            }

            if ($col->type==CollectionColumn::TYPE_FILE)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {

                    if (empty($model[$col_alias]))
                        return '';

                    $ids = json_decode($model[$col_alias],true);

                    $medias = Media::find()->where(['id_media'=>$ids])->all();

                    $output = [];
                    foreach ($medias as $key => $media) {
                        $output[] = '<a href="'.$media->getUrl().'" download>'.$media->name.'</a>';
                    }

                    return implode('', $output);
                };
            }

            if ($col->type==CollectionColumn::TYPE_IMAGE)
            {
                $dataProviderColumns[$col_alias]['format'] = 'raw';
                $dataProviderColumns[$col_alias]['value'] = function($model) use ($col_alias) {

                    if (empty($model[$col_alias]))
                        return '';

                    $ids = json_decode($model[$col_alias],true);

                    $medias = Media::find()->where(['id_media'=>$ids])->all();

                    $output = [];
                    foreach ($medias as $key => $media) {
                        $output[] = '<img src="'.$media->showThumb(['w'=>100,'h'=>100]).'"/>';
                    }

                    return implode('', $output);
                };
            }

            if ($col->type==CollectionColumn::TYPE_DATETIME)
                $dataProviderColumns[$col_alias]['format'] = ['date', 'php:d.m.Y H:i'];

            $sortAttributes[$col_alias] = [
                'asc' => [$col_alias => SORT_ASC],
                'desc' => [$col_alias => SORT_DESC],
                'default' => SORT_ASC
            ];
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
            'sort' => [
                'attributes'=>$sortAttributes,
                'defaultOrder' => [
                    'id_record' => SORT_DESC
                ]
            ]
        ]);

//print_r($sortAttributes);
        /*$dataProvider->setSort([
            'attributes' => $sortAttributes
        ]);*/

         /*$dataProvider->sort->attributes['cat.name'] = [
            'asc' => ['cat.name' => SORT_ASC],
            'desc' => ['cat.name' => SORT_DESC],
        ];*/

        return $this->render('index', [
            'model' => $model,
            'columns' => $dataProviderColumns,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionDownloadDoc($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->collection->form->template))
        {
            $export_path = $model->collection->form->makeDoc($model);

            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
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
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            $prepare = $form->prepareData(true);

            if ($model = $collection->insertRecord($prepare))
                return $this->redirect(['index', 'id' => $model->id_collection]);
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
        $collection = $model->collection;

        $form = new FormDynamic($collection->form);

        if ($form->load(Yii::$app->request->post()) && $form->validate())
        {
            $prepare = $form->prepareData(true);

            $model->data = $form->prepareData(true);

            if ($model->save())
                return $this->redirect(['index', 'id' => $model->id_collection]);
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
