<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191031195017Collectionvision
 */
class M191031195017Collectionvision extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'id_parent_collection', $this->integer());
        $this->addColumn('db_collection', 'filter', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection', 'id_parent_collection');
        $this->dropColumn('db_collection', 'filter');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191031195017Collectionvision cannot be reverted.\n";

        return false;
    }
    */
}
