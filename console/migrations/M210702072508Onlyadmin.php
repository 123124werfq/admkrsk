<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210702072508Onlyadmin
 */
class M210702072508Onlyadmin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'onlyadmin', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210702072508Onlyadmin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210702072508Onlyadmin cannot be reverted.\n";

        return false;
    }
    */
}
