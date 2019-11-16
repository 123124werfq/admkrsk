<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191115004918Servicetarget
 */
class M191115004918Servicetarget extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_target', [
            'id_target' => $this->primaryKey(),
            'id_service' => $this->integer(),
            'id_form' => $this->integer(),
            'name' => $this->string(500),
            'reestr_number' => $this->string(),
            'old' => $this->integer()->defaultValue(0),
            'modified_at' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->alterColumn('service_service', 'name', $this->string(500)); 
        $this->alterColumn('service_service', 'fullname', $this->text()); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191115004918Servicetarget cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191115004918Servicetarget cannot be reverted.\n";

        return false;
    }
    */
}
