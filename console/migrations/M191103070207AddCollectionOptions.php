<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191103070207AddCollectionOptions
 */
class M191103070207AddCollectionOptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'options', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection', 'options');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191103070207AddCollectionOptions cannot be reverted.\n";

        return false;
    }
    */
}
