<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191108042936Inputtypeservice
 */
class M191108042936Inputtypeservice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input_type', 'service_attribute', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191108042936Inputtypeservice cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191108042936Inputtypeservice cannot be reverted.\n";

        return false;
    }
    */
}
