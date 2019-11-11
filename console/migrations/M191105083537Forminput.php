<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105083537Forminput
 */
class M191105083537Forminput extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'id_form', $this->integer());
        $this->dropColumn('form_input', 'fieldname');
        $this->addColumn('form_input', 'fieldname', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191105083537Forminput cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105083537Forminput cannot be reverted.\n";

        return false;
    }
    */
}
