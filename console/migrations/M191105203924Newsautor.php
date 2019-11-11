<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105203924Newsautor
 */
class M191105203924Newsautor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('db_news', 'description', $this->string()->null()); 
        $this->addColumn('db_news', 'id_user', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191105203924Newsautor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105203924Newsautor cannot be reverted.\n";

        return false;
    }
    */
}
