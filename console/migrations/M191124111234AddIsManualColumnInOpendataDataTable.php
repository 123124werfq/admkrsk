<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124111234AddIsManualColumnInOpendataDataTable
 */
class M191124111234AddIsManualColumnInOpendataDataTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_opendata_data', 'is_manual', $this->boolean()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_opendata_data', 'is_manual');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124111234AddIsManualColumnInOpendataTables cannot be reverted.\n";

        return false;
    }
    */
}
