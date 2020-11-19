<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201118222304Softdeletepluginsettings
 */
class M201118222304Softdeletepluginsettings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('settings_plugin_collection', 'deleted_at', $this->integer());
        $this->addColumn('settings_plugin_collection', 'deleted_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201118222304Softdeletepluginsettings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201118222304Softdeletepluginsettings cannot be reverted.\n";

        return false;
    }
    */
}
