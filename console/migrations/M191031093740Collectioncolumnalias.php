<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191031093740Collectioncolumnalias
 */
class M191031093740Collectioncolumnalias extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_column', 'alias', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection_column', 'alias');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191031093740Collectioncolumnalias cannot be reverted.\n";

        return false;
    }
    */
}
