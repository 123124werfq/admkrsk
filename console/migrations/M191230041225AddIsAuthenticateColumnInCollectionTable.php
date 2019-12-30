<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191230041225AddIsAuthenticateColumnInCollectionTable
 */
class M191230041225AddIsAuthenticateColumnInCollectionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'is_authenticate', $this->boolean()->defaultValue(true));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection', 'is_authenticate');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191230041225AddIsAuthenticateColumnInCollectionTable cannot be reverted.\n";

        return false;
    }
    */
}
