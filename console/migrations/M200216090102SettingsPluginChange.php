<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200216090102SettingsPluginChange
 */
class M200216090102SettingsPluginChange extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings_plugin_collection', 'id_model', $this->integer());
        $this->addColumn('settings_plugin_collection', 'model_class', $this->string());
        $this->addColumn('settings_plugin_collection', 'widget_class', $this->string());
        $this->addColumn('settings_plugin_collection', 'id_model_widget', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200216090102SettingsPluginChange cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200216090102SettingsPluginChange cannot be reverted.\n";

        return false;
    }
    */
}
