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

            $dataProviderColumns[$col->id_column] = [
                'label'=>$col->name,
                'attribute'=>$col->id_column,
                'format' => 'text',
            ];

            if ($col->type==CollectionColumn::TYPE_INTEGER)
                $dataProviderColumns[$col->id_column]['format'] = 'integer';

            if ($col->type==CollectionColumn::TYPE_DATE)
                $dataProviderColumns[$col->id_column]['format'] = ['date', 'php:d.m.Y'];

            if ($col->type==CollectionColumn::TYPE_FILE)
            {
                $dataProviderColumns[$col->id_column]['format'] = 'raw';
                $dataProviderColumns[$col->id_column]['value'] = function($model) use ($col) {

                    if (empty($model[$col->id_column]))
                        return '';

                    $ids = json_decode($model[$col->id_column],true);

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
                $dataProviderColumns[$col->id_column]['format'] = 'raw';
                $dataProviderColumns[$col->id_column]['value'] = function($model) use ($col) {

                    if (empty($model[$col->id_column]))
                        return '';

                    $ids = json_decode($model[$col->id_column],true);

                    $medias = Media::find()->where(['id_media'=>$ids])->all();

                    $output = [];
                    foreach ($medias as $key => $media) {
                        $output[] = '<img src="'.$media->showThumb(['w'=>100,'h'=>100]).'"/>';
                    }

                    return implode('', $output);
                };
            }

            if ($col->type==CollectionColumn::TYPE_DATETIME)
                $dataProviderColumns[$col->id_column]['format'] = ['date', 'php:d.m.Y H:i'];

            $sortAttributes[] = [
                $col->id_column => [
                    'asc' => [$col->id_column => SORT_ASC],
                    'desc' => [$col->id_column => SORT_DESC],
                    'default' => SORT_ASC
                ],
            ];
        }

        /*$dataProvider->setSort([
            'attributes' => [
                'product_name' => [
                    'asc' => ['product_name' => SORT_ASC],
                    'desc' => ['product_name' => SORT_DESC],
                    'default' => SORT_ASC
                ],
                'date' => [
                    'asc' => ['date' => SORT_ASC],
                    'desc' => ['date' => SORT_DESC],
                    'default' => SORT_ASC,
                ],
            ],
            'defaultOrder' => [
                'date' => SORT_ASC
            ]
        ]);*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
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

            if ($model = $collection->insertRecord($prepare))
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
        if (($model = Collection::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
