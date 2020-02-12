<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200212222046Collectionidtype
 */
class M200212222046Collectionidtype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'id_type', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200212222046Collectionidtype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200212222046Collectionidtype cannot be reverted.\n";

        return false;
    }
    */
}
