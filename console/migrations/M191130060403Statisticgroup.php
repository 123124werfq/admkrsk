<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191130060403Statisticgroup
 */
class M191130060403Statisticgroup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_group_action', [
            'id_group' => $this->primaryKey(),
            'model_id' => $this->integer()->notNull(),
            'action' => $this->string()->notNull(),
            'model' => $this->string()->notNull(),
            'is_year' => $this->integer()->defaultValue(0),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191130060403Statisticgroup cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191130060403Statisticgroup cannot be reverted.\n";

        return false;
    }
    */
}
