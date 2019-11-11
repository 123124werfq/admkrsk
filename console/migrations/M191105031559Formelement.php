<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105031559Formelement
 */
class M191105031559Formelement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('form_element', [
            'id_element' => $this->primaryKey(),
            'id_form'=>$this->integer(),
            'id_row'=>$this->integer(),
            'id_input'=>$this->integer(),
            'type'=>$this->integer(),
            'content'=>$this->text(),
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
        $this->dropTable('form_element');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105031559Formelement cannot be reverted.\n";

        return false;
    }
    */
}
