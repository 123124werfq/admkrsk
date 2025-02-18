<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200219042459Totalfilesize
 */
class M200219042459Totalfilesize extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'maxfilesize', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200219042459Totalfilesize cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200219042459Totalfilesize cannot be reverted.\n";

        return false;
    }
    */
}
