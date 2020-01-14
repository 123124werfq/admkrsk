<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200107125541Recordhash
 */
class M200107125541Recordhash extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_record', 'data_hash', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200107125541Recordhash cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200107125541Recordhash cannot be reverted.\n";

        return false;
    }
    */
}
