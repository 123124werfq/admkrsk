<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191210045533Reaadonly
 */
class M191210045533Reaadonly extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'readonly', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191210045533Reaadonly cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191210045533Reaadonly cannot be reverted.\n";

        return false;
    }
    */
}
