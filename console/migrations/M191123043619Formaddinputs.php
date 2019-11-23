<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191123043619Formaddinputs
 */
class M191123043619Formaddinputs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'id_page', $this->integer());
        $this->addColumn('form_form', 'url', $this->string());
        $this->addColumn('form_form', 'message_success', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191123043619Formaddinputs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191123043619Formaddinputs cannot be reverted.\n";

        return false;
    }
    */
}
