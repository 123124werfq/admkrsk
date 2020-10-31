<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Menu;
use common\models\Page;

class MenuController extends Controller
{
    public function actionIndex()
    {
        $menus = Menu::find()->where('id_page IS NOT NULL')->all();

        foreach ($menus as $menu)
        {
            $links = $menu->getLinks()->all(); //->where('id_page IS NULL')

            if (empty($menu->page))
                continue;

            foreach ($links as $link)
            {
                if (empty($link->page) || $link->page->id_parent != $menu->page->id_page)
                {
                    $page = new Page;
                    $page->title = $link->label;
                    if (!empty($link->url))
                        $page->alias = $link->url;
                    else if (!empty($link->id_page))
                        $page->id_page_link = $link->id_page;

                    $page->hidemenu = $link->state?0:1;
                    $page->type = Page::TYPE_LINK;
                    $page->id_parent = $menu->id_page;
                    $page->ord = $link->ord;

                    if (!$page->appendTo($menu->page))
                    {
                        print_r($page->errors);
                    }
                }
                else
                {
                    $page = Page::findOne($link->id_page);

                    if (!empty($page))
                    {
                        $page->hidemenu = $link->state?0:1;
                        $page->ord = $link->ord;
                        $page->updateAttributes(['hidemenu','ord']);
                    }
                }
            }
        }
    }
}