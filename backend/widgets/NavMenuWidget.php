<?php
namespace backend\widgets;

use common\models\Page;
use common\models\News;
use yii\base\Widget;
use Yii;

class NavMenuWidget extends Widget
{
    public function run()
    {
        $menu = require __DIR__ . '/../config/menu.php';

        $news_pages = Page::find()->where('id_page IN (SELECT id_page FROM db_news)')->all();

        if (!empty($news_pages))
        {
	        $menu['news']['submenu'] = [];

	        foreach ($news_pages as $key => $page) 
	        {
	        	$menu['news']['submenu']['news?id_page='.$page->id_page] = [
		                'title'=>$page->title,
		                'roles' => ['backend.news'],
				];
	        }
	    }

		return $this->render('navmenu',[
			'menu' => $menu,
			'user' => Yii::$app->user->identity,
			'active_url' => ltrim(str_replace('/master/','',Yii::$app->request->url), '/')
		]);
	}
}
