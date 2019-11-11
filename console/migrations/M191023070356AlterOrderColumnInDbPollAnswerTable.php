<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191023070356AlterOrderColumnInDbPollAnswerTable
 */
class M191023070356AlterOrderColumnInDbPollAnswerTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('db_poll_question', 'order', 'ord');
        $this->renameColumn('db_poll_answer', 'order', 'ord');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('db_poll_question', 'ord', 'order');
        $this->renameColumn('db_poll_answer', 'ord', 'order');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191023070356AlterOrderColumnInDbPollAnswerTable cannot be reverted.\n";

        return false;
    }
    */
}
