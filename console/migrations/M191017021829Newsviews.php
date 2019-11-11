<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191017021829Newsviews
 */
class M191017021829Newsviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'views', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_news', 'views');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191017021829Newsviews cannot be reverted.\n";

        return false;
    }
    */
}
