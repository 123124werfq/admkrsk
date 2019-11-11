<?php

namespace console\migrations\log;

use yii\db\Migration;

/**
 * Class M190904155945AddIndexesToLogTable
 */
class M190904155945AddIndexesToLogTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-log-log_id', '{{%log}}', 'log_id');
        $this->createIndex('idx-log-previous_id', '{{%log}}', 'previous_id');
        $this->createIndex('idx-log-model', '{{%log}}', 'model');
        $this->createIndex('idx-log-model_id', '{{%log}}', 'model_id');
        $this->createIndex('idx-log-created_by', '{{%log}}', 'created_by');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-log-log_id', '{{%log}}');
        $this->dropIndex('idx-log-previous_id', '{{%log}}');
        $this->dropIndex('idx-log-model', '{{%log}}');
        $this->dropIndex('idx-log-model_id', '{{%log}}');
        $this->dropIndex('idx-log-created_by', '{{%log}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190904155945AddIndexesToLogTable cannot be reverted.\n";

        return false;
    }
    */
}
