<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191116223356Inputoptions
 */
class M191116223356Inputoptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('form_input', 'visibleInputValue');
        $this->addColumn('form_input', 'visibleInputValue', $this->text()."[]");

        $this->dropColumn('form_input', 'options');
        $this->addColumn('form_input', 'options', $this->text()."[]");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191116223356Inputoptions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191116223356Inputoptions cannot be reverted.\n";

        return false;
    }
    */
}
