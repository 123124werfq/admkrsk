<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191007185715Isdicchange
 */
class M191007185715Isdicchange extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('db_collection', 'is_dictionary', $this->integer());
        $this->addColumn('db_collection', 'is_dictionary', $this->boolean());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191007185715Isdicchange cannot be reverted.\n";

        return false;
    }
    */
}
