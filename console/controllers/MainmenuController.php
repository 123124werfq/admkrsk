<?php

namespace console\controllers;

use common\models\Page;
use common\models\Menu;
use common\models\MenuLink;
use Yii;
use yii\console\Controller;

class MainmenuController extends Controller
{
    public function actionIndex()
    {
//        $roots = Page::find()->where(['id_parent' => 0])->andWhere(['active' => 1])->andWhere(['noguest' => 0])->all();
        $roots = Page::find()->where(['id_parent' => 0])->andWhere(['noguest' => 0])->all();
        $menu = Menu::find()->where(['alias' => 'subheader_menu'])->one();

        if(!$menu)
            return false;

        MenuLink::deleteAll(['id_menu' => $menu->id_menu]);
        $count = 0;
        $usedIDs = [];

        foreach ($roots as $root)
        {
            //$firstLevelItem = MenuLink::find()->where(['id_page' => $root->id_page])->one();
            $firstLevelItem = false;
            if(!$firstLevelItem)
            {
                $firstLevelItem = new MenuLink();

                $firstLevelItem->label = $root->title;
                $firstLevelItem->id_page = $root->id_page;
                $firstLevelItem->id_menu = $menu->id_menu;
                $firstLevelItem->save();
                $count++;
            }

            $usedIDs[] = $root->id_page;

            foreach ($root->getChilds()->all() as $child)
            {

                //$secondLevelItem = MenuLink::find()->where(['id_page' => $child->id_page])->andWhere(['id_parent' => $firstLevelItem->id_link])->one();
                $secondLevelItem = false;
                if(!$secondLevelItem)
                {
                    $secondLevelItem = new MenuLink();

                    $secondLevelItem->label = $child->title;
                    $secondLevelItem->id_page = $child->id_page;
                    $secondLevelItem->id_menu = $menu->id_menu;
                    $secondLevelItem->id_parent = $firstLevelItem->id_link;
                    $secondLevelItem->save();
                    $count++;
                }
                $usedIDs[] = $child->id_page;
            }

        }

        echo "$count элементов добвалено\n";
/*
        $allItems =  MenuLink::find()->where(['id_menu' => $menu->id_menu])->all();
        $count = 0;

        foreach($allItems as $item)
        {
            if(!in_array($item->id_page, $usedIDs))
            {
                $item->delete();
                $count++;
            }
        }

        echo "$count элементов удалено\n";
*/
    }
}