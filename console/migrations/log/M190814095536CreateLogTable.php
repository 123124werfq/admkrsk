<?php

namespace console\migrations\log;

use yii\db\Migration;

/**
 * Class M190814095536CreateLogTable
 */
class M190814095536CreateLogTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%log}}', [
            'id' => $this->primaryKey(),
            'log_id' => $this->integer(),
            'model' => $this->string(64),
            'model_id' => $this->integer(),
            'rev' => $this->integer(),
            'data' => $this->json(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%log}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190814095536CreateLogTable cannot be reverted.\n";

        return false;
    }
    */
}
