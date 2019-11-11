<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191013192004Menulinkmenu
 */
class M191013192004Menulinkmenu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_menu_link', 'id_menu_content', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_menu_link', 'id_menu_content');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191013192004Menulinkmenu cannot be reverted.\n";

        return false;
    }
    */
}
