<?php
namespace frontend\widgets;

use Yii;
use common\models\News;
use common\models\Menu;
use common\models\Page;

class NewsWidget extends \yii\base\Widget
{
	public $page;
    public $block;

    public function run()
    {
        $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

        if (!empty($blockVars['id_page']))
        {
            $page = Page::findOne($blockVars['id_page']->value);

            if (empty($page))
                return false;

            $news = News::find()->where(['state'=>1,'id_page'=>$page->id_page])
                        ->orderBy('date_publish DESC')->limit(4)->all();

            if (empty($news))
                return false;

            return $this->render('news/news_single',[
                'blockVars'=>$blockVars,
                'news'=>$news,
                'page'=>$page,
            ]);
        }

        if (empty($blockVars['menu']))
            return false;

        $menu = Menu::findOne($blockVars['menu']->value);

        if (empty($menu))
            return false;

        $tabs = [];

        foreach ($menu->links as $key => $link)
        {
            if (!empty($link->id_page))
            {
                $tabs[$link->id_link]['news'] = News::find()
                                                    ->where(['main'=>1,'state'=>1,'id_page'=>$link->id_page])
                                                    ->orderBy('date_publish DESC');

                // если это анонсы
                if (!empty($link->template))
                    $tabs[$link->id_link]['news']->limit(4);
                else
                {
                    $tabs[$link->id_link]['widenews'] = News::find()
                                                            ->where(['main'=>1,'state'=>1,'id_page'=>$link->id_page])
                                                            ->andWhere('id_media IS NOT NULL AND id_media <> 0')
                                                            ->orderBy('date_publish DESC')
                                                            ->one();

                    if (!empty($tabs[$link->id_link]['widenews']))
                        $tabs[$link->id_link]['news']->andWhere('id_news <> '.$tabs[$link->id_link]['widenews']->id_news)->limit(3);
                    else
                        $tabs[$link->id_link]['news']->limit(9);
                }


                $tabs[$link->id_link]['news'] = $tabs[$link->id_link]['news']->all();
            }
            else
                $tabs[$link->id_link]['content'] = $link->content;
        }

        if (empty($tabs))
            return false;

        return $this->render('news/news',[
        	'tabs'=>$tabs,
            'menu'=>$menu,
        ]);
    }
}
