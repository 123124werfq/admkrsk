<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191020065725Menutemplate
 */
class M191020065725Menutemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_menu', 'template', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_menu', 'template');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191020065725Menutemplate cannot be reverted.\n";

        return false;
    }
    */
}
