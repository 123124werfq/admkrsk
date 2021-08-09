<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210809104845GalleriesGroupsRename
 */
class M210809104845GalleriesGroupsRename extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('galleries_groups','id','gallery_group_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210809104845GalleriesGroupsRename cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210809104845GalleriesGroupsRename cannot be reverted.\n";

        return false;
    }
    */
}
