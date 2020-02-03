<?php

namespace console\migrations;

use yii\db\Migration;

class M200123042658CreateTableSubscribers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscribers', [
            'id' => $this->primaryKey(),
            'email' => $this->string(),
            'id_user' => $this->integer(),
            'access_token' => $this->string(),
            'time_subscribe' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscribers');
    }
}
