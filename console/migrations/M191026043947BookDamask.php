<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191026043947BookDamask
 */
class M191026043947BookDamask extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_book', [
            'id_book' => $this->primaryKey(),
            'id_user'=> $this->integer(),
            'damask_number'=> $this->integer(),
            'office'=> $this->string(),
            'operation'=> $this->string(),
            'date'=> $this->string(),
            'time'=> $this->string(),
            'pin'=> $this->string(),
            'state'=> $this->string(),

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
        $this->dropTable('db_book');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191026043947BookDamask cannot be reverted.\n";

        return false;
    }
    */
}
