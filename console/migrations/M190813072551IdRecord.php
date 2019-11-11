<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190813072551IdRecord
 */
class M190813072551IdRecord extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_value', 'id_record', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection_value', 'id_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190813072551IdRecord cannot be reverted.\n";

        return false;
    }
    */
}
