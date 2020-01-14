<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200105103231CreateFiasUpdateHistoryTable
 */
class M200105103231CreateFiasUpdateHistoryTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('fias_update_history', [
            'id' => $this->primaryKey(),
            'version' => $this->integer(),
            'text' => $this->string(),
            'file' => $this->string(),
//            'all_count' => $this->integer(),
//            'manual_count' => $this->integer(),
//            'insert_count' => $this->integer(),
//            'update_count' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('fias_update_history');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200105103231CreateFiasUpdateHistoryTable cannot be reverted.\n";

        return false;
    }
    */
}
