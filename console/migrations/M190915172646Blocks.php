<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190915172646Blocks
 */
class M190915172646Blocks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
          $this->createTable('db_block', [
            'id_block' => $this->primaryKey(),
            'id_page' => $this->integer(),
            'widget' => $this->string(),
            'alias' => $this->string(),
            'code' => $this->text(),
            'state' => $this->integer(),
            'ord' => $this->integer(),
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
        $this->dropTable('db_block');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190915172646Blocks cannot be reverted.\n";

        return false;
    }
    */
}
