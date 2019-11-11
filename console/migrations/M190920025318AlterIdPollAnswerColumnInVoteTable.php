<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190920025318AlterIdPollAnswerColumnInVoteTable
 */
class M190920025318AlterIdPollAnswerColumnInVoteTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%db_poll_vote}}', 'id_poll_answer', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%db_poll_vote}}', 'id_poll_answer', $this->integer()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190920025318AlterIdPollAnswerColumnInVoteTable cannot be reverted.\n";

        return false;
    }
    */
}
