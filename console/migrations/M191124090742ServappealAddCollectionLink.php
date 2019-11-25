<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124090742ServappealAddCollectionLink
 */
class M191124090742ServappealAddCollectionLink extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_appeal', 'id_record', $this->integer());
        $this->addColumn('service_appeal', 'id_collection', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        echo "M191124090742ServappealAddCollectionLink cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124090742ServappealAddCollectionLink cannot be reverted.\n";

        return false;
    }
    */
}
