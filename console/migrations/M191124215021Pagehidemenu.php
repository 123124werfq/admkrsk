<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124215021Pagehidemenu
 */
class M191124215021Pagehidemenu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'hidemenu', $this->integer()->defaultValue(0));
        $this->addColumn('cnt_page', 'name_short', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191124215021Pagehidemenu cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124215021Pagehidemenu cannot be reverted.\n";

        return false;
    }
    */
}
