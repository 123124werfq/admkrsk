<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105105522Serviceappeal
 */
class M191105105522Serviceappeal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_appeal', [
            'id_appeal' => $this->primaryKey(),
            'id_user'=>$this->integer(),
            'id_service'=>$this->integer(),
            'state' => $this->string()->notNull(),
            'date' => $this->integer(),
            'data' => $this->text(),
            'archive' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('service_appeal_state', [
            'id_state' => $this->primaryKey(),
            'id_appeal' => $this->integer(),
            'date' => $this->integer(),
            'state' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191105105522Serviceappeal cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105105522Serviceappeal cannot be reverted.\n";

        return false;
    }
    */
}
