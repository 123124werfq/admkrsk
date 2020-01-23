<?php

namespace console\migrations;

use yii\db\Migration;

class M200123044021CreateTableSubscribersNotify extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscribers_notify', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer(),
            'page_id' => $this->integer(),
            'news_id' => $this->integer(),
            'isNotify' => $this->boolean(),
            'created_at' => $this->timestamp(),
            'notify_at' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscribers_notify');
    }
}
