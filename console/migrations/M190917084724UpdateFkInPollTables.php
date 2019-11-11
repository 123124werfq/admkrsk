<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190917084724UpdateFkInPollTables
 */
class M190917084724UpdateFkInPollTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-db_poll_question-id_poll-db_poll-id_poll', '{{%db_poll_question}}');
        $this->dropForeignKey('fk-db_poll_answer-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_answer}}');
        $this->dropForeignKey('fk-db_poll_vote-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_vote}}');
        $this->dropForeignKey('fk-db_poll_vote-id_poll_answer-db_poll_answer-id_poll_answer', '{{%db_poll_vote}}');

        $this->addForeignKey('fk-db_poll_question-id_poll-db_poll-id_poll', '{{%db_poll_question}}', 'id_poll', '{{%db_poll}}', 'id_poll', 'CASCADE');
        $this->addForeignKey('fk-db_poll_answer-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_answer}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question', 'CASCADE');
        $this->addForeignKey('fk-db_poll_vote-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_vote}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question', 'CASCADE');
        $this->addForeignKey('fk-db_poll_vote-id_poll_answer-db_poll_answer-id_poll_answer', '{{%db_poll_vote}}', 'id_poll_answer', '{{%db_poll_answer}}', 'id_poll_answer', 'CASCADE');
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


        $this->addForeignKey('fk-db_poll_question-id_poll-db_poll-id_poll', '{{%db_poll_question}}', 'id_poll', '{{%db_poll}}', 'id_poll');
        $this->addForeignKey('fk-db_poll_answer-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_answer}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question');
        $this->addForeignKey('fk-db_poll_vote-id_poll_question-db_poll_question-id_poll_question', '{{%db_poll_vote}}', 'id_poll_question', '{{%db_poll_question}}', 'id_poll_question');
        $this->addForeignKey('fk-db_poll_vote-id_poll_answer-db_poll_answer-id_poll_answer', '{{%db_poll_vote}}', 'id_poll_answer', '{{%db_poll_answer}}', 'id_poll_answer');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190917084724UpdateFkInPollTables cannot be reverted.\n";

        return false;
    }
    */
}
