<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191027083506Forms
 */
class M191027083506Forms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('form_form', [
            'id_form' => $this->primaryKey(),
            'id_collection'=>$this->integer(),
            'name'=>$this->string()->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('form_row', [
            'id_row' => $this->primaryKey(),
            'id_form' => $this->integer(),
            'ord'=>$this->string()->notNull(),
            'content'=>$this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('form_input_type', [
            'id_type' => $this->primaryKey(),
            'id_collection'=>$this->integer(),
            'name'=>$this->string()->notNull(),
            'regexp'=>$this->string(),
            'options'=>$this->text(),
            'type'=>$this->integer(),
            'esia'=>$this->integer(),
            'values'=>$this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('form_input', [
            'id_input' => $this->primaryKey(),
            'id_row' => $this->integer(),
            'id_type' => $this->integer(),
            'id_collection'=>$this->integer(),
            'name'=>$this->string()->notNull(),
            'fieldname'=>$this->string()->notNull(),
            'visibleInput'=>$this->integer(),
            'visibleInputValue'=>$this->string(),
            'values'=>$this->text(),
            'size'=>$this->integer(),
            'options'=>$this->text(),
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
        $this->dropTable('form_form');
        $this->dropTable('form_row');
        $this->dropTable('form_input_type');
        $this->dropTable('form_input');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191027083506Forms cannot be reverted.\n";

        return false;
    }
    */
}
