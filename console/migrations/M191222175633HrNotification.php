<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191222175633HrNotification
 */
class M191222175633HrNotification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('hr_contest', 'notification', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191222175633HrNotification cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191222175633HrNotification cannot be reverted.\n";

        return false;
    }
    */
}
