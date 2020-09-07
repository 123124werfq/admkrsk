<?php

namespace backend\controllers;

use common\models\Action;
use common\models\GridSetting;
use common\modules\log\models\Log;
use moonland\phpexcel\Excel;
use Yii;
use common\models\News;
use common\models\Page;
use backend\models\search\NewsSearch;
use yii\base\ExitException;
use yii\base\InvalidConfigException;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    const grid = 'news-grid';

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
                        'roles' => ['backend.news.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.news.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.news.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update'],
                        'roles' => ['backend.news.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.news.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.news.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => News::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.news.log.view', 'backend.entityAccess'],
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
                        'roles' => ['backend.news.log.restore', 'backend.entityAccess'],
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
     * @param $id_page
     * @param int $export
     * @return mixed
     * @throws ExitException
     * @throws InvalidConfigException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionIndex($id_page=null, $export = 0)
    {
        $news_pages = Page::find()
            ->andFilterWhere(['id_page' => Page::getAccessPageIds()])
            ->andWhere(['or',['type'=>Page::TYPE_NEWS],['type'=>Page::TYPE_ANONS]])
            ->orderBy('title ASC')
            ->all();

        if (empty($id_page) && !empty($news_pages))
        {
            return $this->redirect(['index','id_page'=>$news_pages[0]->id_page]);
        }

         /*(!empty($news_pages))
        {
            $menu['news']['submenu'] = [];

            foreach ($news_pages as $key => $page) {
                $menu['news']['submenu']['news?id_page=' . $page->id_page] = [
                    'title' => $page->title,
                    'roles' => [
                        'backend.entityAccess' => [
                            'entity_id' => $page->id_page,
                            'class' => Page::class,
                        ],
                    ],
                ];
            }
        }*/

        if (!$id_page || ($page = Page::findOne($id_page)) === null) {
            throw new NotFoundHttpException();
        } elseif (!Page::hasEntityAccess($page->id_page)) {
            throw new ForbiddenHttpException();
        }

        $searchModel = new NewsSearch();
        $searchModel->id_page = $id_page;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $grid = GridSetting::findOne([
            'class' => static::grid,
            'user_id' => Yii::$app->user->id,
        ]);
        $columns = null;
        if ($grid) {
            $columns = json_decode($grid->settings, true);
        }

        if ($export)
        {
            header('Content-Type: text/xlsx; charset=utf-8');
            header('Content-Disposition: attachment; filename=Выгрузка новостей '.$page->title.'.xlsx');

            Excel::widget([
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
            'news_pages'=>$news_pages,
            'customColumns' => $columns,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
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
     * @param $id_page
     * @return mixed
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCreate($id_page)
    {
        if (!$id_page || ($page = Page::findOne($id_page)) === null) {
            throw new NotFoundHttpException();
        } elseif (!Page::hasEntityAccess($page->id_page)) {
            throw new ForbiddenHttpException();
        }

        $model = new News();
        $model->main = $model->state = 1;
        $model->id_page = $id_page;
        $model->id_user = Yii::$app->user->id;
        $model->date_publish = time();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
            'subtitle' => $page->title??'Новости'
        ]);
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     * @throws InvalidConfigException
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
     * @throws StaleObjectException
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

        return $this->redirect(['index', 'id_page' => $model->id_page, 'archive' => 1]);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = News::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
