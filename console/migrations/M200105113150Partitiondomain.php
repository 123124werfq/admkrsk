<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200105113150Partitiondomain
 */
class M200105113150Partitiondomain extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'partition_domain', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200105113150Partitiondomain cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200105113150Partitiondomain cannot be reverted.\n";

        return false;
    }
    */
}
