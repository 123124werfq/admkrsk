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
                        'actions' => ['layout'],
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
                        'actions' => ['update'],
                        'roles' => ['backend.page.update'],
                        'roleParams' => [
                            'entity_id' => Yii::$app->request->get('id'),
                            'class' => Page::class,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['delete'],
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
    public function actionList($q)
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

        if (!empty($_GET['news']))
            $query->andWhere('id_page IN (SELECT id_page FROM db_news)');

        $results = [];
        foreach ($query->limit(10)->all() as $page) {

            $results[] = [
                'id' => $page->id_page,
                'text' => $page->title,
            ];
        }

        return ['results' => $results];
    }

    public function actionLayout($id)
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

        foreach ($pages as $key => $page) {
            $tree[(int)$page->id_parent][$page->id_page] = $page;
        }

        return $this->render('tree',['tree'=>$tree]);
    }
    /**
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
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

        $ords = Yii::$app->request->post('ords');

        if (!empty($ords))
        {
            foreach ($ords as $key => $id)
                Yii::$app->db->createCommand()->update('cnt_page',['ord'=>$key],['id_page'=>$id])->execute();
        }

        $submenu = [];

        if (empty($model->menu))
            $submenu = $model->getChilds();
        else
            $submenu = $model->menu->getLinks();

        $dataProvider = new ActiveDataProvider([
            'query' => $submenu,
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
        $model->id_parent = $id_parent;

        if (!empty($model->id_parent) && !empty($model->parent))
            $model->ord = count($model->parent->getSubMenu());

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            $model->createAction(Action::ACTION_CREATE);
            return $this->redirect(['view', 'id' => ($id_parent)?$id_parent:$model->id_page]);
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->createAction(Action::ACTION_UPDATE);
            return $this->redirect(['view', 'id' => $model->id_page]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
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
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
