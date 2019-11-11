<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191025054024Servicesituation
 */
class M191025054024Servicesituation extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_situation', [
            'id_situation' => $this->primaryKey(),
            'id_media'=>$this->integer(),
            'id_parent'=>$this->integer(),
            'name'=>$this->string(),
            'ord'=>$this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('service_rubric', [
            'id_rub' => $this->primaryKey(),
            'id_parent'=>$this->integer(),
            'name'=>$this->string(),
            'ord'=>$this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('service_situation');
        $this->dropTable('service_rubric');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191025054024Servicesituation cannot be reverted.\n";

        return false;
    }
    */
}
