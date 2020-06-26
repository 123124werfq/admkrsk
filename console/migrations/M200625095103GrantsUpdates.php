<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200625095103GrantsUpdates
 */
class M200625095103GrantsUpdates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cst_profile', 'additional_status', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200625095103GrantsUpdates cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200625095103GrantsUpdates cannot be reverted.\n";

        return false;
    }
    */
}
