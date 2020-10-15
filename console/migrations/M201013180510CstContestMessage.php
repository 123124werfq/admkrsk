<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201013180510CstContestMessage
 */
class M201013180510CstContestMessage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cst_contest_message', [
            'id_contest_message' => $this->primaryKey(),
            'id_contest' => $this->integer(),
            'message' => $this->text(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201013180510CstContestMessage cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201013180510CstContestMessage cannot be reverted.\n";

        return false;
    }
    */
}
