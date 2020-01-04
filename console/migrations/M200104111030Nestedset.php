<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200104111030Nestedset
 */
class M200104111030Nestedset extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'lft', $this->integer());
        $this->addColumn('cnt_page', 'rgt', $this->integer());
        $this->addColumn('cnt_page', 'depth', $this->integer());

        $this->createIndex('lft', 'cnt_page', ['lft', 'rgt']);
        $this->createIndex('rgt', 'cnt_page', ['rgt']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200104111030Nestedset cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200104111030Nestedset cannot be reverted.\n";

        return false;
    }
    */
}
