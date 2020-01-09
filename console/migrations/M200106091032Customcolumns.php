<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200106091032Customcolumns
 */
class M200106091032Customcolumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_column', 'template', $this->text());
        $this->addColumn('db_collection_column', 'keep_relation', $this->boolean()->defaultValue(true));

        $this->createTable('dbl_collection_record', [
            'id_record' => $this->integer()->notNull(),
            'id_record_from' => $this->integer()->notNull(),
            'id_column' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200106091032Customcolumns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200106091032Customcolumns cannot be reverted.\n";

        return false;
    }
    */
}
