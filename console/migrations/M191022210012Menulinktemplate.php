<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191022210012Menulinktemplate
 */
class M191022210012Menulinktemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_menu_link', 'template', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_menu_link', 'template');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191022210012Menulinktemplate cannot be reverted.\n";

        return false;
    }
    */
}
