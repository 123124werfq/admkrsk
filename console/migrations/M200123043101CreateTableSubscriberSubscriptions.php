<?php

namespace console\migrations;

use yii\db\Migration;

class M200123043101CreateTableSubscriberSubscriptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('subscriber_subscriptions', [
            'id' => $this->primaryKey(),
            'subscriber_id' => $this->integer(),
            'page_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('subscriber_subscriptions');
    }
}
