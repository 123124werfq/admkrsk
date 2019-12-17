<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191217060044Serviceforms
 */
class M191217060044Serviceforms extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'show_forms', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191217060044Serviceforms cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191217060044Serviceforms cannot be reverted.\n";

        return false;
    }
    */
}
