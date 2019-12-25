<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\News;
use common\models\Page;
use backend\models\search\NewsSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['backend.news.index'],
                        'roleParams' => [
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.news.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.news.create'],
                        'roleParams' => [
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.news.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.news.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.news.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.news.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.news.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => News::class,
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
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex($id_page,$export=0)
    {
        $searchModel = new NewsSearch();
        $searchModel->id_page = $id_page;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $page = Page::findOne($id_page);

        if ($export)
        {
            header('Content-Type: text/xlsx; charset=utf-8');
            header('Content-Disposition: attachment; filename=Выгрузка новостей '.$page->title.'.xlsx');

            \moonland\phpexcel\Excel::widget([
                'models' => $dataProvider->query->all(),
                'mode' => 'export',
                'columns' => [
                    [
                        'attribute'=>'title',
                        'width'=>100,
                    ],
                    [
                        'attribute'=>'fullurl',
                        'width'=>80,
                    ],
                    'date_publish:date',
                    'date_unpublish:date',
                    'created_at:date',
                    'updated_at:date',
                    'views',
                    'viewsYear',
                'views'],
                'headers' => [
                    'title' => 'Название',
                    'fullurl' => 'Ссылка',
                    'date_unpublish'=> 'Снять с публикации',
                    'date_publish'=> 'Опубликовать',
                    'created_at'=> 'Создано',
                    'updated_at'=> 'Отредактировано',
                    'views'=>'Просмотры всего',
                    'viewsYear'=>'Просмотры за год',
                ],
            ]);

            Yii::$app->end();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'page' => $page,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id
)    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
        $model->main = $model->state = 1;
        $model->id_page = Yii::$app->request->get('id_page',null);
        $model->id_user = Yii::$app->user->id;
        $model->date_publish = time();

        $id_page = Yii::$app->request->get('id_page');

        if ($id_page)
        {
            $model->id_page = $id_page;
            $news_pages = Page::findOne($id_page);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // убираем подсветку
            if ($model->highlight)
                Yii::$app->db->createCommand()->update('db_news',['highlight'=>0],['highlight'=>1,'id_page'=>$model->id_page])->execute();

            if ($model->save())
            {
                $model->createAction(Action::ACTION_CREATE);

                return $this->redirect(['index', 'id_page' => $model->id_page]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'subtitle' => $news_pages->title??'Новости'
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->tagNames = [];
        foreach ($model->tags as $key => $tag)
            $model->tagNames[$tag->name] = $tag->name;

        $model->pages = $model->getPages()->indexBy('id_page')->all();
        $model->pages = array_keys($model->pages);

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            // убираем подсветку
            if ($model->highlight)
                Yii::$app->db->createCommand()->update('db_news',['highlight'=>0],['highlight'=>1,'id_page'=>$model->id_page])->execute();

            if ($model->save())
            {
                $model->createAction(Action::ACTION_UPDATE);
                return $this->redirect(['index', 'id_page' => $model->id_page]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing News model.
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

        return $this->redirect(['index', 'id_page' => $model->id_page]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUndelete($id)
    {
        $model = $this->findModel($id);

        if ($model->restore()) {
            $model->createAction(Action::ACTION_UNDELETE);
        }

        return $this->redirect(['index', 'id_page' => $model->id_page, 'archive' => 1]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
