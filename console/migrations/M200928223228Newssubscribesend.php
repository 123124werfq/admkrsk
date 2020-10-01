<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200928223228Newssubscribesend
 */
class M200928223228Newssubscribesend extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'send_subscribe', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200928223228Newssubscribesend cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200928223228Newssubscribesend cannot be reverted.\n";

        return false;
    }
    */
}
