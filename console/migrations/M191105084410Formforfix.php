<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105084410Formforfix
 */
class M191105084410Formforfix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('form_row', 'ord');
        $this->addColumn('form_row', 'ord', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191105084410Formforfix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105084410Formforfix cannot be reverted.\n";

        return false;
    }
    */
}
