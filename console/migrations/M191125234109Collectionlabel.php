<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191125234109Collectionlabel
 */
class M191125234109Collectionlabel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'label', $this->integer().'[]');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191125234109Collectionlabel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191125234109Collectionlabel cannot be reverted.\n";

        return false;
    }
    */
}
