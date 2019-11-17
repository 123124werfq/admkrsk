<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191117085114Collectinform
 */
class M191117085114Collectinform extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'id_form', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191117085114Collectinform cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191117085114Collectinform cannot be reverted.\n";

        return false;
    }
    */
}
