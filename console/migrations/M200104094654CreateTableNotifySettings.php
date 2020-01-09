<?php

namespace console\migrations;

use yii\db\Migration;

class M200104094654CreateTableNotifySettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notify_settings', [
            'id' => $this->primaryKey(),
            'class' => $this->string(),
            'message' => $this->string(),
            'main_notify' => $this->integer(),
            'repeat_notify' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('notify_settings');
    }
}
