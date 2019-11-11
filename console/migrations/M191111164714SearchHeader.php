<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191111164714SearchHeader
 */
class M191111164714SearchHeader extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('search_sitemap', 'header', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('search_sitemap', 'header');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191111164714SearchHeader cannot be reverted.\n";

        return false;
    }
    */
}
