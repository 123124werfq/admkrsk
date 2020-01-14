<?php

namespace console\migrations;

use yii\db\Migration;
use \common\models\Page;
/**
 * Class M200104111659Treecreate
 */
class M200104111659Treecreate extends Migration
{
    public $parentAttribute = 'id_parent';
    public $leftAttribute = 'lft';
    public $rightAttribute = 'rgt';
    public $depthAttribute = 'depth';

    protected function tree($parent)
    {
        if (!empty($parent->childs))
        {
            foreach ($parent->childs as $key => $child)
            {
                $child->appendTo($parent);
                echo "$child->title > $parent->title \r\n";
                flush();

                $this->tree($child);
            }
        }
    }

    public function rebuildTree($lft = null, $depth = null, $page)
    {
        echo "$page->title";
        flush();

        // Load initial values from current node, when not specified
        if ($lft === null) {
            $lft = max(1, $page->getAttribute($this->leftAttribute));
        }
        if ($depth === null) {
            $depth = max(0, $page->getAttribute($this->depthAttribute));
        }
        // The right value of this node is the left value + 1
        $rgt = $lft + 1;
        $children = $page->findAll([$this->parentAttribute => $page->primaryKey]);
        foreach ($children as $child) {
            $rgt = $this->rebuildTree($rgt, $depth + 1,$child);
        }
        // Store new values
        $page->setAttribute($this->leftAttribute, $lft);
        $page->setAttribute($this->rightAttribute, $rgt);
        $page->setAttribute($this->depthAttribute, $depth);

        $page->save(false, [$this->leftAttribute, $this->rightAttribute, $this->depthAttribute]);

        return $rgt + 1;
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $root = Page::find()->where(['alias'=>'/'])->one();
        $root->lft = 0;
        $root->rgt = 0;
        $root->depth = 0;
        $root->updateAttributes(['lft','rgt','depth']);
        //$root->makeRoot();

        $pages = Page::find()->where('id_parent = 0 AND id_page <> '.$root->id_page)->orderBy('ord')->all();

        foreach ($pages as $key => $page0)
        {
            $page0->id_parent = $root->id_page;
            $page0->updateAttributes(['id_parent']);
            /*if (!$page0->appendTo($root))
                die($page0->errors);

            $this->tree($page0);*/
        }

        $this->rebuildTree(null,null,$root);
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
