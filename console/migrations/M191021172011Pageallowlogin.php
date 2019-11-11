<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191021172011Pageallowlogin
 */
class M191021172011Pageallowlogin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'noguest', $this->smallInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cnt_page', 'noguest');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191021172011Pageallowlogin cannot be reverted.\n";

        return false;
    }
    */
}
