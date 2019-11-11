<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191107191632Inputtypeesia
 */
class M191107191632Inputtypeesia extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('form_input_type', 'esia', $this->string()->null()); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191107191632Inputtypeesia cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191107191632Inputtypeesia cannot be reverted.\n";

        return false;
    }
    */
}
