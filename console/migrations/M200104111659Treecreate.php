<?php

namespace console\migrations;

use yii\db\Migration;
use \common\models\Page;
/**
 * Class M200104111659Treecreate
 */
class M200104111659Treecreate extends Migration
{

    protected function tree($parent)
    {
        if (!empty($parent->childs))
        {
            foreach ($parent->childs as $key => $child)
            {
                $child->appendTo($parent)->save();
                echo "$child->title > $parent->title \r\n";
                flush();

                $this->tree($child);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $root = Page::find()->where(['alias'=>'/'])->one();
        $root->lft = 1;
        $root->rgt = 2;
        $root->depth = 1;
        $root->updateAttributes(['lft','rgt','depth']);

        $pages = Page::find()->where('id_parent = 0 AND id_page <> '.$root->id_page)->orderBy('ord')->all();
        echo "string";
        foreach ($pages as $key => $page0)
        {
            if (!$page0->appendTo($root)->save())
                die($page0->errors);

            $this->tree($page0);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200104111659Treecreate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200104111659Treecreate cannot be reverted.\n";

        return false;
    }
    */
}
