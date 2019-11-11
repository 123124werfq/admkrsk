<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191012082857AddOdataTables
 */
class M191012082857AddOpendataTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_opendata', [
            'id_opendata' => $this->primaryKey(),
            'id_collection' => $this->integer(),
            'id_user' => $this->integer(),
            'id_page' => $this->integer(),
            'identifier' => $this->string(),
            'title' => $this->string(),
            'description' => $this->text(),
            'owner' => $this->string(),
            'keywords' => $this->string(),
            'columns' => $this->json(),
            'period' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('db_opendata_structure', [
            'id_opendata_structure' => $this->primaryKey(),
            'id_opendata' => $this->integer(),
            'signature' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('db_opendata_data', [
            'id_opendata_data' => $this->primaryKey(),
            'id_opendata_structure' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createIndex('idx-db_opendata-id_collection', 'db_opendata', 'id_collection');
        $this->createIndex('idx-db_opendata_structure-id_opendata', 'db_opendata_structure', 'id_opendata');
        $this->createIndex('idx-db_opendata_structure-id_opendata-signature', 'db_opendata_structure', ['id_opendata', 'signature'], true);
        $this->createIndex('idx-db_opendata_data-id_opendata_structure', 'db_opendata_data', 'id_opendata_structure');

        $this->addForeignKey('fk-db_opendata-id_collection-db_collection-id_collection', 'db_opendata', 'id_collection', 'db_collection', 'id_collection', 'CASCADE');
        $this->addForeignKey('fk-db_opendata-id_user-user-id', 'db_opendata', 'id_user', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-db_opendata-id_page-cnt_page-id_page', 'db_opendata', 'id_page', 'cnt_page', 'id_page', 'SET NULL');
        $this->addForeignKey('fk-db_opendata_structure-id_opendata-db_opendata-id_opendata', 'db_opendata_structure', 'id_opendata', 'db_opendata', 'id_opendata', 'CASCADE');
        $this->addForeignKey('fk-db_opendata_data-id_opendata_structure-db_opendata_structure-id_opendata_structure', 'db_opendata_data', 'id_opendata_structure', 'db_opendata_structure', 'id_opendata_structure', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-db_opendata-id_collection-db_collection-id_collection', 'db_opendata');
        $this->dropForeignKey('fk-db_opendata-id_user-user-id', 'db_opendata');
        $this->dropForeignKey('fk-db_opendata-id_page-cnt_page-id_page', 'db_opendata');
        $this->dropForeignKey('fk-db_opendata_structure-id_opendata-db_opendata-id_opendata', 'db_opendata_structure');
        $this->dropForeignKey('fk-db_opendata_data-id_opendata_structure-db_opendata_structure-id_opendata_structure', 'db_opendata_data');

        $this->dropTable('db_opendata');
        $this->dropTable('db_opendata_structure');
        $this->dropTable('db_opendata_data');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191012082857AddOdataTables cannot be reverted.\n";

        return false;
    }
    */
}
