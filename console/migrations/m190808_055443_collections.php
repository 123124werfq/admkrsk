<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m190808_055443_collections
 */
class m190808_055443_collections extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_collection', [
            'id_collection' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string(),
            'is_dictionary' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_collection_column', [
            'id_column' => $this->primaryKey(),
            'id_collection' => $this->integer()->notNull(),
            'id_dictionary' => $this->integer(),
            'name' => $this->string()->notNull(),
            'type' => $this->integer(),
            'show_column_admin' => $this->integer(),
            'ord' => $this->integer(),
        ]);

        $this->createTable('db_collection_value', [
            'id_column' => $this->integer(),
            'id_record' => $this->integer(),
            'value' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_collection_value');
        $this->dropTable('db_collection_column');
        $this->dropTable('db_collection');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190808_055443_collections cannot be reverted.\n";

        return false;
    }
    */
}
