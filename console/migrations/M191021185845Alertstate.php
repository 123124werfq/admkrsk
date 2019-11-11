<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191021185845Alertstate
 */
class M191021185845Alertstate extends Migration
{
    /**
     * {@inheritdoc}
     */
     public function safeUp()
    {
        $this->addColumn('db_alert', 'state', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_alert', 'state');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191021185845Alertstate cannot be reverted.\n";

        return false;
    }
    */
}
