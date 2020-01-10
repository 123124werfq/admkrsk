<?php

namespace console\migrations;

use yii\db\Migration;

class M200104093835CreateTableMessage extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notify_messages', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'class' => $this->string(),
            'message' => $this->string(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('notify_messages');
    }
}
