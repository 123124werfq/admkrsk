<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191204043903Columnoptions
 */
class M191204043903Columnoptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_column', 'options', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191204043903Columnoptions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191204043903Columnoptions cannot be reverted.\n";

        return false;
    }
    */
}
