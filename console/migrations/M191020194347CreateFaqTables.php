<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191020194347CreateFaqTables
 */
class M191020194347CreateFaqTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_faq_category', [
            'id_faq_category' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_faq', [
            'id_faq' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull(),
            'question' => $this->text()->notNull(),
            'answer' => $this->text()->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_faq_faq_category', [
            'id_faq' => $this->integer()->notNull(),
            'id_faq_category' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-db_faq_faq_category-id_faq-db_faq-id_faq', 'db_faq_faq_category', 'id_faq', 'db_faq', 'id_faq', 'CASCADE');
        $this->addForeignKey('fk-db_faq_faq_category-id_faq_category-db_faq_category-id_faq_category', 'db_faq_faq_category', 'id_faq_category', 'db_faq_category', 'id_faq_category', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-db_faq_faq_category-id_faq-db_faq-id_faq', 'db_faq_faq_category');
        $this->dropForeignKey('fk-db_faq_faq_category-id_faq_category-db_faq_category-id_faq_category', 'db_faq_faq_category');

        $this->dropTable('db_faq_category');
        $this->dropTable('db_faq');
        $this->dropTable('db_faq_faq_category');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191020194347CreateFaqTable cannot be reverted.\n";

        return false;
    }
    */
}
