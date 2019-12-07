<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191207105845Formgroup
 */
class M191207105845Formgroup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191207105845Formgroup cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191207105845Formgroup cannot be reverted.\n";

        return false;
    }
    */
}
