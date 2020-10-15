<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201015192301MailLog
 */
class M201015192301MailLog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_maillog', [
            'id_maillog' => $this->primaryKey(),
            'email' => $this->text(),
            'message' => $this->text(),
            'source' => $this->text(),
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
        echo "M201015192301MailLog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201015192301MailLog cannot be reverted.\n";

        return false;
    }
    */
}
