<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191130064825IntegrationsLog
 */
class M191130064825IntegrationsLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_intergation', [
            'id_integration' => $this->primaryKey(),
            'system' => $this->integer(),
            'direction' => $this->integer(),
            'status' => $this->integer(),
            'operation' => $this->string(),
            'description' => $this->string(),
            'data_type' => $this->string(),
            'data' => $this->text(),
            'created_at' => $this->integer(),
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
        echo "M191130064825IntegrationsLog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191130064825IntegrationsLog cannot be reverted.\n";

        return false;
    }
    */
}
