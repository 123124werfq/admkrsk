<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210809102126GalleriesGroupsJunctionSoft
 */
class M210809102126GalleriesGroupsJunctionSoft extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('galleries_groups', 'deleted_at', $this->integer());
        $this->addColumn('galleries_groups', 'deleted_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210809102126GalleriesGroupsJunctionSoft cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210809102126GalleriesGroupsJunctionSoft cannot be reverted.\n";

        return false;
    }
    */
}
