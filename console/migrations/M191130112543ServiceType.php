<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191130112543ServiceType
 */
class M191130112543ServiceType extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'type', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191130112543ServiceType cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191130112543ServiceType cannot be reverted.\n";

        return false;
    }
    */
}
