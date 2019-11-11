<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190928090553Vars
 */
class M190928090553Vars extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('cnt_vars', [
            'id_var' => $this->primaryKey(),
            'name' => $this->string(),
            'alias' => $this->string(),
            'content' => $this->text(),
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
        $this->dropTable('cnt_vars');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190928090553Vars cannot be reverted.\n";

        return false;
    }
    */
}
