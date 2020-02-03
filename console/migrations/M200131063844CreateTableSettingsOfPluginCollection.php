<?php

namespace console\migrations;

use yii\db\Migration;

class M200131063844CreateTableSettingsOfPluginCollection extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('settings_plugin_collection', [
            'id' => $this->primaryKey(),
            'id_collection' => $this->integer(),
            'id_page' => $this->integer(),
            'key' => $this->string(),
            'settings' => $this->string(200000),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('settings_plugin_collection');
    }
}
