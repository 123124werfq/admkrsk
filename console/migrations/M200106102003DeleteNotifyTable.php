<?php

namespace console\migrations;

use yii\db\Migration;

class M200106102003DeleteNotifyTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tables = \Yii::$app->db->schema->getTableNames();
        if (in_array('notify_settings', $tables)) {
            $this->dropTable('notify_settings');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
