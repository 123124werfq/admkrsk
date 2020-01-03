<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200103203446CreateStatisticTable
 */
class M200103203446CreateStatisticTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('db_group_action');

        $this->createTable('db_statistic', [
            'id_statistic' => $this->primaryKey(),
            'model' => $this->string()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'year' => $this->integer(),
            'views' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_statistic');

        $this->createTable('db_group_action', [
            'id_group' => $this->primaryKey(),
            'model_id' => $this->integer()->notNull(),
            'action' => $this->string()->notNull(),
            'model' => $this->string()->notNull(),
            'is_year' => $this->integer()->defaultValue(0),
        ]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200103203446CreateStatisticTable cannot be reverted.\n";

        return false;
    }
    */
}
