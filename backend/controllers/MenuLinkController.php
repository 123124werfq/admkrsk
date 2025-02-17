<?php

namespace backend\controllers;

use Yii;
use common\models\Menu;
use common\models\MenuLink;
use common\models\Page;

use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MenuLinkController implements the CRUD actions for MenuLink model.
 */
class MenuLinkController extends Controller
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
     * Lists all MenuLink models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $model = Menu::findOneWithDeleted($id);

        if (empty($model))
            throw new NotFoundHttpException('The requested page does not exist.');

        $dataProvider = new ActiveDataProvider([
            'query' => MenuLink::find()->where(['id_menu'=>$id])->orderBy('ord'),
        ]);

        if ($model->type == Menu::TYPE_LEVELS)
            return $this->render('levels', [
                'records' => $dataProvider->query->andWhere('id_parent IS NULL')->all(),
                'model'=>$model,
            ]);
        else
            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'model'=>$model,
            ]);
    }

    /**
     * Displays a single MenuLink model.
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

    public function actionHide($id)
    {
        $model = $this->findModel($id);
        $model->state = $model->state?0:1;
        $model->updateAttributes(['state']);

        if (!empty($model->menu->id_page))
            return $this->redirect(['page/view', 'id' => $model->menu->id_page]);
        else
            return $this->redirect(['index', 'id' => $model->id_menu]);
    }

    /**
     * Creates a new MenuLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_menu=null,$id=null,$id_page=null)
    {
        if (empty($id_menu) && !empty($id_page))
        {
            $page = Page::findOne($id_page);

            if (empty($page))
                throw new NotFoundHttpException('The requested page does not exist.');

            if (empty($page->menu))
            {
                $menu = new Menu();
                $menu->name = 'Меню раздела '.$page->id_page;
                $menu->alias = 'Menu_'.$page->id_page.'_'.time();
                $menu->type = Menu::TYPE_LIST;
                $menu->id_page = $id_page;
                $menu->save();

                foreach ($page->childs as $key => $child)
                    $menu->addLink($child,$child->ord);
            }
            else
                $menu = $page->menu;
        }
        else
            $menu = Menu::findOneWithDeleted($id_menu);

        if (empty($menu))
            throw new NotFoundHttpException('The requested page does not exist.');

        $model = new MenuLink();
        $model->id_menu = $menu->id_menu;
        $model->id_parent = $id;

        $model->state = 1;

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if (!empty($id_page))
                return $this->redirect(['page/view', 'id' => $id_page]);
            else
                return $this->redirect(['index', 'id' => $model->id_menu]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MenuLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id,$id_page=null)
    {
        $model = $this->findModel($id);

        $this->actionOrder();

        if ($model->load(Yii::$app->request->post()) && $model->save())
        {
            if (Yii::$app->request->isAjax)
                Yii::$app->end();

            if (!empty($model->menu->page))
                return $this->redirect(['page/view', 'id' => $model->menu->id_page]);
            else
                return $this->redirect(['index', 'id' => $model->id_menu]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MenuLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!empty($model->menu->page))
            $page = $model->menu->page;

        $model->delete();

        if (!empty($page))
            return $this->redirect(['page/view', 'id' => $page->id_page]);

        return $this->redirect(['index']);
    }

    public function actionOrder()
    {
        $ords = Yii::$app->request->post('ords');

        if(!empty($ords))
            foreach ($ords as $key => $id)
                Yii::$app->db->createCommand()->update('db_menu_link',['ord'=>$key],['id_link'=>$id])->execute();
    }

    /**
     * Finds the MenuLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MenuLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MenuLink::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
