<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210809103801GalleriesGroupsJunctionRename
 */
class M210809103801GalleriesGroupsJunctionRename extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('galleries_groups_junction','gallery_id','id_gallery');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210809103801GalleriesGroupsJunctionRename cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210809103801GalleriesGroupsJunctionRename cannot be reverted.\n";

        return false;
    }
    */
}
