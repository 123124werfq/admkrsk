<?php

namespace frontend\controllers;

use Yii;
use common\models\News;
use common\models\CollectionRecord;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;

class NewsController extends \yii\web\Controller
{
    public function actionIndex($page=null)
    {
        $news = News::find();

        $rubrics = (new \yii\db\Query())
                ->select(['id_rub'])
                ->from('db_news')
                ->where('id_rub IS NOT NULL');

        if (!empty($page))
        {
            $news->where(['id_page'=>$page->id_page])->orWhere("id_news IN (SELECT id_news FROM dbl_news_page WHERE id_page = $page->id_page)");
            $rubrics->andWhere(['id_page'=>$page->id_page]);
        }

        $id_rub = Yii::$app->request->get('id_rub');

        $rubrics = $rubrics->groupBy('id_rub')->column();

        // фильтр рубрики
        if (!empty($id_rub))
            $news->andWhere(['id_rub'=>$id_rub]);

        // фильтры тега
        if (!empty(Yii::$app->request->get('tag')))
            $news->joinWith('tags as tags')->where(['tags.name'=>Yii::$app->request->get('tag')]);

        // получаем рубрики из коллекции
        if (!empty($rubrics))
            $rubrics = CollectionRecord::find()->where(['id_record'=>$rubrics])->all();

        $pagination = new Pagination(['totalCount' => $news->count(), 'pageSize'=>20]);

        $totalCount = $news->count();

        $news = $news->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy('date_publish DESC')
            ->all();

        return $this->render('index',[
            'page'=>$page,
            'id_rub'=>$id_rub,
            'news'=>$news,
            'totalCount'=>$totalCount,
            'rubrics'=>$rubrics,
        ]);
    }

    public function actionView($id,$page=null)
    {
    	$model = News::findOne($id);

    	if (empty($model))
    		throw new NotFoundHttpException('The requested page does not exist.');

        // мета данные
        $this->view->title = $model->title;

        Yii::$app->view->registerMetaTag([
            'name' => 'description',
            'content' => $model->description
        ]);

        if (!empty($model->id_rub))
            $similar_news = News::find()->where(['id_rub'=>$model->id_rub,'id_page'=>$model->id_page])->andWhere('id_news <> '.$id)->limit(3)->all();
        else
            $similar_news = News::find()->where(['id_page'=>$model->id_page])->andWhere('id_news <> '.$id)->limit(3)->all();

        $model->createAction();

        return $this->render('view',[
        	'model'=>$model,
            'page'=>$page,
            'similar_news'=>$similar_news,
        ]);
    }
}
