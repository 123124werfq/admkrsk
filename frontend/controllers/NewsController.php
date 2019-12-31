<?php

namespace frontend\controllers;

use Yii;
use common\models\News;
use common\models\CollectionRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
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

        $id_rub = (int)Yii::$app->request->get('id_rub');
        $date = Yii::$app->request->get('date');

        $rubrics = $rubrics->groupBy('id_rub')->column();

        // фильтр даты
        if (!empty($date))
        {
            $dates = explode('-', $date);

            if (count($dates)==2)
            {
                $date_begin = strtotime(trim($dates[0]));
                $date_begin = mktime(0,0,0,date('m',$date_begin),date('d',$date_begin),date('Y',$date_begin));

                $date_end = strtotime(trim($dates[1]));
                $date_end = mktime(24,59,59,date('m',$date_end),date('d',$date_end),date('Y',$date_end));

                $news->andWhere(['>=','date_publish',$date_begin]);
                $news->andWhere(['<=','date_publish',$date_end]);
            }
        }

        // фильтр рубрики
        if (!empty($id_rub))
            $news->andWhere(['id_rub'=>$id_rub]);

        // фильтры тега
        if (!empty(Yii::$app->request->get('tag')))
            $news->joinWith('tags as tags')->where(['tags.name'=>Yii::$app->request->get('tag')]);

        // получаем рубрики из коллекции
        if (!empty($rubrics))
            $rubrics = CollectionRecord::find()->where(['id_record'=>$rubrics])->all();

        //$pagination = new Pagination(['totalCount' => $news->count(), 'pageSize'=>20]);

        $totalCount = $news->count();

        $news = $news
            //->offset($pagination->offset)
            ->limit(20)//$pagination->limit
            ->offset((int)Yii::$app->request->get('p',0)*20)
            ->orderBy('date_publish DESC')
            ->all();

        if (Yii::$app->request->isAjax)
        {
            $output = '';
            foreach ($news as $key => $data)
                $output .= $this->renderPartial('_news',['data'=>$data]);

            return $output;
        }

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

        $model->logUserAction();

        return $this->render('view',[
        	'model'=>$model,
            'page'=>$page,
            'similar_news'=>$similar_news,
        ]);
    }
}
