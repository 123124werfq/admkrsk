<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190930174748Blockvariables
 */
class M190930174748Blockvariables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('db_block_var', [
            'id_var' => $this->primaryKey(),
            'id_block' => $this->integer(),
            'id_media' => $this->integer(),
            'type' => $this->integer(),
            'name' => $this->string(),
            'alias' => $this->string(),
            'value' => $this->text(),
        ]);

         /*$this->createTable('db_block_value', [
            'id_var' => $this->primaryKey(),
            'value' => $this->text(),
        ]);*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_block_var');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190930174748Blockvariables cannot be reverted.\n";

        return false;
    }
    */
}
