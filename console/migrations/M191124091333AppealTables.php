<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124091333AppealTables
 */
class M191124091333AppealTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('appeal_request', [
            'id_request' => $this->primaryKey(),
            'id_record' => $this->integer(),
            'is_anonimus' => $this->smallInteger()->defaultValue(0),
            'id_user' => $this->integer(),
            'comment' => $this->text(),
            'number_internal' => $this->text(),
            'number_system' => $this->text(),
            'number_common' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('appeal_state', [
            'id_state' => $this->primaryKey(),
            'id_request' => $this->integer(),
            'state' => $this->text(),
            'archive' => $this->smallInteger()->defaultValue(0),
            'workflow_message' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->addColumn('service_appeal', 'number_internal', $this->text());
        $this->addColumn('service_appeal', 'number_system', $this->integer());
        $this->addColumn('service_appeal', 'number_common', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191124091333AppealTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124091333AppealTables cannot be reverted.\n";

        return false;
    }
    */
}
