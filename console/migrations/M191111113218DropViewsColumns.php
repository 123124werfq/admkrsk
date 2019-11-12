<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191111113218DropViewsColumns
 */
class M191111113218DropViewsColumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('cnt_page', 'views');
        $this->dropColumn('db_news', 'views');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('cnt_page', 'views', $this->integer());
        $this->addColumn('db_news', 'views', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191111113218DropViewsColumns cannot be reverted.\n";

        return false;
    }
    */
}
