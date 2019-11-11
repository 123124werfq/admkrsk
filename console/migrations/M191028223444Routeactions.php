<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191028223444Routeactions
 */
class M191028223444Routeactions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('db_controller_page', 'actions');
        $this->addColumn('db_controller_page', 'actions', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191028223444Routeactions cannot be reverted.\n";

        return false;
    }
    */
}
