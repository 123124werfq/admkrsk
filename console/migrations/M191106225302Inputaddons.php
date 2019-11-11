<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191106225302Inputaddons
 */
class M191106225302Inputaddons extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'required', $this->integer()->defaultValue(0));
        $this->addColumn('form_input', 'hint', $this->string());
        //$this->addColumn('form_element', 'size', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191106225302Inputaddons cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191106225302Inputaddons cannot be reverted.\n";

        return false;
    }
    */
}
