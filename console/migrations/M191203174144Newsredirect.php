<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191203174144Newsredirect
 */
class M191203174144Newsredirect extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'url', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191203174144Newsredirect cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191203174144Newsredirect cannot be reverted.\n";

        return false;
    }
    */
}
