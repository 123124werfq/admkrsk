<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200509005341AlterSignatureColumnInDbOpendataStructureTable
 */
class M200509005341AlterSignatureColumnInDbOpendataStructureTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%db_opendata_structure}}', 'signature', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('{{%db_opendata_structure}}', 'signature', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200509005341AlterSignatureColumnInDbOpendataStructureTable cannot be reverted.\n";

        return false;
    }
    */
}
