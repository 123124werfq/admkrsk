<?php

namespace console\migrations\log;

use yii\db\Migration;

/**
 * Class M190904155742AddPreviousIdColumnToLogTable
 */
class M190904155742AddPreviousIdColumnToLogTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%log}}', 'previous_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%log}}', 'previous_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190904155742AddPreviousIdColumnToLogTable cannot be reverted.\n";

        return false;
    }
    */
}
