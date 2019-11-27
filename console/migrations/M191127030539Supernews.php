<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191127030539Supernews
 */
class M191127030539Supernews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'highlight', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191127030539Supernews cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191127030539Supernews cannot be reverted.\n";

        return false;
    }
    */
}
