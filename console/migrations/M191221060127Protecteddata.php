<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191221060127Protecteddata
 */
class M191221060127Protecteddata extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_column', 'protected', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191221060127Protecteddata cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191221060127Protecteddata cannot be reverted.\n";

        return false;
    }
    */
}
