<?php

namespace backend\controllers;

use backend\models\forms\CopyPageForm;
use common\models\Action;
use common\models\GridSetting;
use common\models\MenuLink;
use common\modules\log\models\Log;

use moonland\phpexcel\Excel;
use Yii;
use common\models\Page;
use common\models\Block;
use backend\models\search\PageSearch;
use yii\base\ExitException;
use yii\base\InvalidConfigException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\validators\NumberValidator;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use yii\web\Response;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
{
    const grid = 'page-grid';

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
                        'roles' => ['backend.page.layout', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'list', 'tree', 'partition'],
                        'roles' => ['backend.page.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => ['backend.page.view', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'roles' => ['backend.page.create', 'backend.entityAccess'],
                        'roleParams' => [
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update','copy','hide','order','get-page'],
                        'roles' => ['backend.page.update', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete', 'undelete'],
                        'roles' => ['backend.page.delete', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['history'],
                        'roles' => ['backend.page.log.index', 'backend.entityAccess'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['log'],
                        'roles' => ['backend.page.log.view', 'backend.entityAccess'],
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
                        'roles' => ['backend.page.log.restore', 'backend.entityAccess'],
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
     * @param int $partition
     * @param null $type
     * @return mixed
     * @throws InvalidConfigException
     */
    public function actionList($q = '', $partition = 0, $type = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Page::find();

        if (!Yii::$app->user->can('admin.page')) {
            $query->andWhere(['id_page' => Page::getAccessEntityIds()]);
        }

        if ((new NumberValidator(['integerOnly' => true]))->validate($q)) {
            $query->andWhere([
                'or',
                ['id_page' => $q],
                ['ilike', 'title', $q],
            ]);

            $query->andWhere(['id' => $q]);
        } else if (!empty($q))
            $query->andWhere(['ilike', 'title', $q]);

        if (!empty($partition))
            $query->andWhere(['is_partition' => true]);

        if (!empty($type) && $type == 'news')
            $query->andWhere(['or',['type'=>Page::TYPE_NEWS],['type'=>Page::TYPE_ANONS]]);

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

    public function actionGetPage()
    {
        $records = Page::find()->where('id_page IN (SELECT id_page FROM db_news)')->all();

        $pages = [];
        $selectedPage = null;
        $pageId = Yii::$app->request->get('page-id');
        /** @var Page $page */
        foreach ($records as $key => $page) {
            $pageData = [
                'text' => $page->title,
                'value' => (string)$page->id_page,
            ];
            if ($pageId == $page->id_page) {
                $selectedPage = $pageData;
                continue;
            }
            $pages[] = $pageData;
        }
        if ($selectedPage) {
            array_unshift($pages, $selectedPage);
        }

        return json_encode($pages);
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
            //$tree[(int)$page->id_parent][$page->id_page] = $page;

            $tree = [
                "id"=>$page->id_page, 
                "parent"=>$page->id_parent?:'#', 
                "text"=>$page->title,
            ];
        }

        return $this->render('tree',['tree'=>$tree]);
    }

    public function actionPartition($id=null)
    {
        $model = null;

        if (!empty($id))
        {
            $model = $this->findModel($id);
            $partitions = $model->children()->andWhere(['is_partition'=>1])->all();

            return $this->render('partition/partition',[
                'partitions'=>$partitions,
                'model'=>$model,
            ]);
        }
        else
        {
            $partitions = Page::find()->where(['is_partition'=>1])->all();

            return $this->render('partition/all',[
                'partitions'=>$partitions,
            ]);
        }
    }

    /**
     * Lists all Page models.
     * @return mixed
     * @throws ExitException
     */
    public function actionIndex($id_partition=null,$export=false)
    {
        $searchModel = new PageSearch();
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
            header('Content-Disposition: attachment; filename=Выгрузка разделы.xlsx');

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
            'partition'=>($id_partition)?$this->findModel($id_partition):null,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'customColumns' => $columns,
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
     * @throws InvalidConfigException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        //$submenu = [];

        //if (empty($model->menu))
        $submenu = $model->getChilds()->orderBy('ord');
        /*else
            $submenu = $model->menu->getLinks()->orderBy('ord');*/

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
     * @param null $id_parent
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCreate($id_parent=null,$is_link=0)
    {
        $model = new Page();
        $model->created_at = time();

        if ($is_link)
            $model->type = Page::TYPE_LINK;

        if (!empty($id_parent))
        {
            $model->id_parent = $id_parent;
            $parent = $this->findModel($id_parent);

            if (Page::hasEntityAccess($parent->id_page)) {
                $model->populateRelation('parent',$parent);
            } else {
                throw new ForbiddenHttpException();
            }
        } elseif (!Yii::$app->user->can('admin.page')) {
            throw new ForbiddenHttpException();
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
     * @throws InvalidConfigException
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

    /**
     * @param $id
     * @return mixed
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     */
    public function actionCopy($id)
    {
        $model = $this->findModel($id);

        $copyForm = new CopyPageForm(['id_page' => $model->id_page]);

        if ($copyForm->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();

            try {
                $newPage = $copyForm->cloneNode();
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }

            return $this->redirect(['view', 'id' => $newPage->id_page]);
        }

        return $this->render('copy', [
            'model' => $model,
            'copyForm' => $copyForm,
        ]);
    }

    /**
     * Deletes an existing Page model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id, $redirect=null)
    {
        $model = $this->findModel($id);

        if (!empty($model->parent->menu))
        {
            $link = MenuLink::find()->where(['id_page' => $id,'id_menu'=>$model->parent->menu->id_menu])->one();
            $link->delete();
        }

        if ($model->delete())
            $model->createAction(Action::ACTION_DELETE);

        if (!empty($redirect))
            return $this->redirect(['view','id'=>$redirect]);

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
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidConfigException
     */
    protected function findModel($id)
    {
        if (($model = Page::findOneWithDeleted($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionHide($id)
    {
        $model = $this->findModel($id);

        $model->hidemenu = ($model->hidemenu)?0:1;
        $model->updateAttributes(['hidemenu']);

        return $this->redirect(['view', 'id' => $model->id_parent]);
    }
}
