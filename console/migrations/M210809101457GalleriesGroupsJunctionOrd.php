<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210809101457GalleriesGroupsJunctionOrd
 */
class M210809101457GalleriesGroupsJunctionOrd extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('galleries_groups_junction', 'ord', $this->integer()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210809101457GalleriesGroupsJunctionOrd cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210809101457GalleriesGroupsJunctionOrd cannot be reverted.\n";

        return false;
    }
    */
}
