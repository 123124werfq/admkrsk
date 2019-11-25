<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124201043Servicetargetadd
 */
class M191124201043Servicetargetadd extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_target', 'place', $this->string());
        $this->addColumn('service_target', 'state', $this->string());
        $this->dropColumn('service_target', 'old');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191124201043Servicetargetadd cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124201043Servicetargetadd cannot be reverted.\n";

        return false;
    }
    */
}
