<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191110215554Pageord
 */
class M191110215554Pageord extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'ord', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191110215554Pageord cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191110215554Pageord cannot be reverted.\n";

        return false;
    }
    */
}
