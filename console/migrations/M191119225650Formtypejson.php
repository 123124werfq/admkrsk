<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191119225650Formtypejson
 */
class M191119225650Formtypejson extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('form_input', 'options');
        $this->addColumn('form_input', 'options', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191119225650Formtypejson cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191119225650Formtypejson cannot be reverted.\n";

        return false;
    }
    */
}
