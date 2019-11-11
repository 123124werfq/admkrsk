<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191021182702Pagealert
 */
class M191021182702Pagealert extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_alert', [
            'id_alert' => $this->primaryKey(),
            'id_page'=> $this->integer(),
            'content'=> $this->text(),
            'date_begin'=> $this->integer(),
            'date_end'=> $this->integer(),
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
        $this->dropTable('db_alert');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191021182702Pagealert cannot be reverted.\n";

        return false;
    }
    */
}
