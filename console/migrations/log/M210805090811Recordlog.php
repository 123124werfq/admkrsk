<?php

namespace console\migrations\log;

use yii\db\Migration;

/**
 * Class M210805090811Recordlog
 */
class M210805090811Recordlog extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('log_record', [
            'id' => $this->primaryKey(),
            'id_record' => $this->integer(),
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
        echo "M210805090811Recordlog cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210805090811Recordlog cannot be reverted.\n";

        return false;
    }
    */
}
