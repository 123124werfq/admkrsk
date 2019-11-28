<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\Page;
use common\models\Menu;
use common\models\MenuLink;

/**
 * Class M191128081452Menufix
 */
class M191128081452Menufix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $menus = Menu::find()->where('id_page IS NOT NULL')->all();

        foreach ($menus as $key => $menu)
        {
            $page = $menu->page;

            foreach ($page->childs as $key => $child)
            {
                $link = MenuLink::find()->where([
                    'id_menu'=>$menu->id_menu,
                    'id_page'=>$child->id_page])->one();


                if (empty($link))
                {
                    $link = new MenuLink;
                    $link->id_menu = $menu->id_menu;
                    $link->id_page = $child->id_page;
                    //$link->state = 1-$child->hidemenu;
                    $link->label = $child->title;
                    $link->ord = $child->ord;

                    echo "$link->label -    --";

                    if (!$link->save())
                        print_r($link->errors);
                }
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191128081452Menufix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191128081452Menufix cannot be reverted.\n";

        return false;
    }
    */
}
