<?php

namespace console\migrations\log;

use yii\db\Migration;

/**
 * Class M191111103914AddActionTable
 */
class M191111103914AddActionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('action', [
            'id' => $this->primaryKey(),
            'model' => $this->string(),
            'model_id' => $this->integer(),
            'action' => $this->string(),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('action');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191111103914AddActionTable cannot be reverted.\n";

        return false;
    }
    */
}
