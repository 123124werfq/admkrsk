<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191208044412Stateform
 */
class M191208044412Stateform extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->addColumn('form_form', 'state', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191208044412Stateform cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191208044412Stateform cannot be reverted.\n";

        return false;
    }
    */
}
