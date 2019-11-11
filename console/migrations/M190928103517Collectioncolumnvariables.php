<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190928103517Collectioncolumnvariables
 */
class M190928103517Collectioncolumnvariables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_column', 'variables', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection_column', 'variables');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190928103517Collectioncolumnvariables cannot be reverted.\n";

        return false;
    }
    */
}
