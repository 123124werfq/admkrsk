<?php

namespace console\migrations;

use yii\db\Migration;

class M200111134718CreateTableGridSettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('grid_settings', [
            'id' => $this->primaryKey(),
            'class' => $this->string(),
            'user_id' => $this->integer(),
            'settings' => $this->json(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('grid_settings');
    }
}
