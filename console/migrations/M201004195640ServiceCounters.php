<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201004195640ServiceCounters
 */
class M201004195640ServiceCounters extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%db_service_counter}}', [
            'id_counter' => $this->primaryKey(),
            'service_number' => $this->string(),
            'value' => $this->integer(),
            'period' => $this->integer(),
            'update_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201004195640ServiceCounters cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201004195640ServiceCounters cannot be reverted.\n";

        return false;
    }
    */
}
