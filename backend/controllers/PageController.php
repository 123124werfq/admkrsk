<?php

namespace backend\controllers;

use common\models\Action;
use common\modules\log\models\Log;
use Yii;
use common\models\Page;
use common\models\Block;
use backend\models\search\PageSearch;
use yii\filters\AccessControl;
use yii\validators\NumberValidator;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\Response;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
                        'actions' => ['layout','template'],
                        'roles' => ['backend.page.layout'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'tree'],
                        'roles' => ['backend.page.index'],
                        'roleParams' => [
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.page.view'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.page.create'],
                        'roleParams' => [
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','hide','order'],
                        'roles' => ['backend.page.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.page.delete'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.page.log.index'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.page.log.view'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['restore'],
                        'roles' => ['backend.page.log.restore'],
                        'roleParams' => [
                            'entity_id' => function () {
                                if (($log = Log::findOne(Yii::$app->request->get('id'))) !== null) {
                                    return $log->model_id;
                                }
                                return null;
                            },
                            'class' => Page::class,
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
                'modelClass' => Page::class,
            ],
            'log' => [
                'class' => 'backend\modules\log\actions\LogAction',
                'modelClass' => Page::class,
            ],
            'restore' => [
                'class' => 'backend\modules\log\actions\RestoreAction',
                'modelClass' => Page::class,
            ],
        ];
    }

    /**
     * Search Page models.
     * @param string $q
     * @return mixed
     */
    public function actionList($q,$partition=0)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Page::find();

        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere([
                'or',
                ['id_page' => $q],
                ['ilike', 'title', $q],
            ]);
            $query->andWhere(['id' => $q]);
        } else {
            $query->andWhere(['ilike', 'title', $q]);
        }

        if (!empty($partition))
            $query->andWhere(['is_partition' => true]);

        if (!empty($_GET['news']))
            $query->andWhere('id_page IN (SELECT id_page FROM db_news)');

        $results = [];

        foreach ($query->limit(10)->all() as $page)
            $results[] = [
                'id' => $page->id_page,
                'text' => $page->title,
            ];

        return ['results' => $results];
    }

    public function actionTemplate($id)
    {
        $page = $this->findModel($id);
        $block = new Block;
        $block->id_page = $id;
        $blocks = $page->blocks;

        if ($block->load(Yii::$app->request->post()) && $block->validate())
        {
            $block->ord = $page->getBlocks()->count()-1;

            if ($block->save())
                $this->refresh();
            //return $this->renderPartial('/block/_view',['data'=>$model]);
        }

        return $this->render('template',[
            'model'=>$page,
            'block'=>$block,
            'blocks'=>$blocks,
        ]);
    }

    public function actionLayout($id)
    {
        $page = $this->findModel($id);
        $block = new Block;
        $block->id_page_layout = $id;
        $blocks = $page->blocksLayout;

        if ($block->load(Yii::$app->request->post()) && $block->validate())
        {
            $block->ord = $page->getBlocksLayout()->count()-1;

            if ($block->save())
                $this->refresh();
        }

        return $this->render('layout',[
            'model'=>$page,
            'block'=>$block,
            'blocks'=>$blocks,
        ]);
    }

    public function actionTree()
    {
        $pages = Page::find()->all();

        $tree = [];

        foreach ($pages as $key => $page)
        {
            $tree[(int)$page->id_parent][$page->id_page] = $page;
        }

        return $this->render('tree',['tree'=>$tree]);
    }


    public function actionPartition($id=null)
    {
        /*if (!empty($id))
            $this->findModel($id);
        else
            Page::*/
    }

    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex($export=false)
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($export)
        {
            header('Content-Type: text/xlsx; charset=utf-8');
            header('Content-Disposition: attachment; filename=Выгрузка разделы.xlsx');

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
                    'created_at:date',
                    'updated_at:date',
                    'views',
                    'viewsYear',
                ],
                'headers' => [
                    'title' => 'Название',
                    'fullurl' => 'Ссылка',
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
        ]);
    }

    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');

        foreach ($ords as $key => $id)
            Yii::$app->db->createCommand()->update('cnt_page',['ord'=>$key],['id_page'=>$id])->execute();
    }

    /**
     * Displays a single Page model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $submenu = [];

        if (empty($model->menu))
            $submenu = $model->getChilds()->orderBy('ord');
        else
            $submenu = $model->menu->getLinks()->orderBy('ord');

        $dataProvider = new ActiveDataProvider([
            'query' => $submenu,
            'pagination' => [
                'pageSize' => 10000,
            ],
        ]);

        return $this->render('view', [
            'model' => $model,
            'submenu' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_parent=null)
    {
        $model = new Page();
        $model->created_at = time();

        if (!empty($id_parent))
        {
            $model->id_parent = $id_parent;
            $parent = $this->findModel($id_parent);
            $model->populateRelation('parent',$parent);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $parentPage = $this->findModel($model->id_parent);

            if ($model->appendTo($parentPage))
            {
                $model->createAction(Action::ACTION_CREATE);

                if (!empty($model->id_parent) && !empty($model->parent->menu))
                    $model->parent->menu->addLink($model);

                return $this->redirect(['view', 'id' => ($id_parent)?$id_parent:$model->id_page]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->id_parent = $old_parent = null;

        if (!empty($model->parent->id_page))
            $old_parent = $model->id_parent = $model->parent->id_page;

        //print_r(array_keys($model->parents()->indexBy('id_page')->all()));

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $model->createAction(Action::ACTION_UPDATE);

            if ($old_parent != $model->id_parent)
            {
                $parentPage = Page::findOne($model->id_parent);

                if (!empty($parentPage))
                    $model->appendTo($parentPage);
            }

            return $this->redirect(['view', 'id' => $model->id_page]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionHide($id)
    {
        $model = $this->findModel($id);

        $model->hidemenu = ($model->hidemenu)?0:1;
        $model->updateAttributes(['hidemenu']);

        return $this->redirect(['view', 'id' => $model->id_parent]);
    }

    /**
     * Deletes an existing Page model.
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

        return $this->redirect(['index', 'archive' => 1]);
    }

    /**
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
