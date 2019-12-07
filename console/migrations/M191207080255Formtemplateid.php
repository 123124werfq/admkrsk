<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191207080255Formtemplateid
 */
class M191207080255Formtemplateid extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_visibleinput', 'id_element', $this->integer());
        $this->dropColumn('form_visibleinput', 'id_input');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191207080255Formtemplateid cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191207080255Formtemplateid cannot be reverted.\n";

        return false;
    }
    */
}
