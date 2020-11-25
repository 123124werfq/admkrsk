<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201123220615Widgetmodelsettings
 */
class M201123220615Widgetmodelsettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings_plugin_collection', 'model_attribute', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201123220615Widgetmodelsettings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201123220615Widgetmodelsettings cannot be reverted.\n";

        return false;
    }
    */
}
