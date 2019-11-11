<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190901125121Collection_record
 */
class M190901125121Collection_record extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_collection_record', [
            'id_record' => $this->primaryKey(),
            'id_collection' => $this->integer(),
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
        $this->dropTable('db_collection_value');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190901125121Collection_record cannot be reverted.\n";

        return false;
    }
    */
}
