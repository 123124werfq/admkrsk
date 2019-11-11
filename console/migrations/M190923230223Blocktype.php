<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190923230223Blocktype
 */
class M190923230223Blocktype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_block', 'type', $this->string());
        $this->addColumn('db_block', 'name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_block', 'type');
        $this->dropColumn('db_block', 'name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190923230223Blocktype cannot be reverted.\n";

        return false;
    }
    */
}
