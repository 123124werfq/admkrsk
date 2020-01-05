<?php

namespace console\migrations;

use yii\db\Migration;

class M200102120036CreateTableMailNotify extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notify_users', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'class' => $this->string(),
            'created_at' => $this->integer(20),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('notify_users');
    }
}
