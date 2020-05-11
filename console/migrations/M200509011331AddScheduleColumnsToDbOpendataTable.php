<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200509011331AddScheduleColumnsToDbOpendataTable
 */
class M200509011331AddScheduleColumnsToDbOpendataTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%db_opendata}}', 'schedule_settings', $this->json());

        $this->dropColumn('{{%db_opendata}}', 'period');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%db_opendata}}', 'schedule_settings');

        $this->addColumn('{{%db_opendata}}', 'period', $this->smallInteger());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200509011331AddScheduleColumnsToDbOpendataTable cannot be reverted.\n";

        return false;
    }
    */
}
