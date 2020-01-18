<?php
namespace frontend\widgets;

use Yii;
use common\models\News;
use common\models\Page;

class PagenewsWidget extends \yii\base\Widget
{
	public $page;
    public $block;

    public function run()
    {
        if (!empty($this->attributes['id']))
            $this->form = Page::findOne($this->attributes['id']);

       $news = $news
            //->offset($pagination->offset)
            ->limit(20)//$pagination->limit
            ->offset((int)Yii::$app->request->get('p',0)*20)
            ->orderBy('date_publish DESC')
            ->all();

        return $this->render('pagenews',[
            'menu'=>$menu,
        ]);
    }
}
