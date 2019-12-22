<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191222181746HrNotificationSent
 */
class M191222181746HrNotificationSent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('hr_contest', 'notification_sent', $this->integer());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191222181746HrNotificationSent cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191222181746HrNotificationSent cannot be reverted.\n";

        return false;
    }
    */
}
