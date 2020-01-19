<?php
namespace frontend\widgets;

use Yii;
use common\models\News;
use common\models\Page;

class PagenewsWidget extends \yii\base\Widget
{
	public $attributes;
    public $page;

    public function run()
    {
        if (!empty($this->attributes['id']))
            $page = Page::findOne($this->attributes['id']);

        if (empty($page))
            return false;

        $news = News::find()
            ->where(['state'=>1])
            ->andWhere(['id_page'=>$page->id_page])->orWhere("id_news IN (SELECT id_news FROM dbl_news_page WHERE id_page = $page->id_page)")
            ->limit(20)//$pagination->limit
            //->offset((int)Yii::$app->request->get('p',0)*20)
            ->orderBy('date_publish DESC')
            ->all();

        return $this->render('pagenews',[
            'news'=>$news,
            'page'=>$page,
        ]);
    }
}
