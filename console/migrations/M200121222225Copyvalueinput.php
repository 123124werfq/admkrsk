<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200121222225Copyvalueinput
 */
class M200121222225Copyvalueinput extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'id_input_copy', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200121222225Copyvalueinput cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200121222225Copyvalueinput cannot be reverted.\n";

        return false;
    }
    */
}
