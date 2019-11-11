<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191027071757Contollerpage
 */
class M191027071757Contollerpage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_controller_page', [
            'id' => $this->primaryKey(),
            'id_page'=>$this->integer(),
            'controller'=>$this->string(),
            'actions'=>$this->text(),
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
        $this->dropTable('db_controller_page');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191027071757Contollerpage cannot be reverted.\n";

        return false;
    }
    */
}
