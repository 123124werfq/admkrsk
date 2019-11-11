<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190909001610CreatePollTables
 */
class M190909001610CreatePollTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%db_poll}}', [
            'id_poll' => $this->primaryKey(),
            'status' => $this->smallInteger()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text(),
            'result' => $this->text(),
            'is_anonymous' => $this->boolean(),
            'is_hidden' => $this->boolean(),
            'date_start' => $this->integer(),
            'date_end' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('{{%db_poll_question}}', [
            'id_poll_question' => $this->primaryKey(),
            'id_poll' => $this->integer()->notNull(),
            'type' => $this->smallInteger(),
            'question' => $this->text()->notNull(),
            'description' => $this->text(),
            'order' => $this->integer(),
            'is_option' => $this->boolean(),
            'is_hidden' => $this->boolean(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk-db_poll_question-id_poll-db_poll-id_poll', '{{%db_poll_question}}', 'id_poll', '{{%db_poll}}', 'id_poll');

        $this->createTable('{{%db_poll_answer}}', [
            'id_poll_answer' => $this->primaryKey(),
            'id_poll_question' => $this->integer(),
            'answer' => $this->text()->notNull(),
            'description' => $this->text(),
            'order' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk-db_poll_answer-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_answer}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question');

        $this->createTable('{{%db_poll_vote}}', [
            'id_poll_vote' => $this->primaryKey(),
            'id_poll_question' => $this->integer()->notNull(),
            'id_poll_answer' => $this->integer()->notNull(),
            'option' => $this->text(),
            'ip' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk-db_poll_vote-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_vote}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question');
        $this->addForeignKey('fk-db_poll_vote-id_poll_answer-db_poll_answer-id_poll_answer', '{{%db_poll_vote}}', 'id_poll_answer', '{{%db_poll_answer}}', 'id_poll_answer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-db_poll_question-id_poll-db_poll-id_poll', '{{%db_poll_question}}');
        $this->dropForeignKey('fk-db_poll_answer-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_answer}}');
        $this->dropForeignKey('fk-db_poll_vote-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_vote}}');
        $this->dropForeignKey('fk-db_poll_vote-id_poll_answer-db_poll_answer-id_poll_answer', '{{%db_poll_vote}}');

        $this->dropTable('{{%db_poll}}');
        $this->dropTable('{{%db_poll_question}}');
        $this->dropTable('{{%db_poll_answer}}');
        $this->dropTable('{{%db_poll_vote}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190909001610CreatePollTables cannot be reverted.\n";

        return false;
    }
    */
}
