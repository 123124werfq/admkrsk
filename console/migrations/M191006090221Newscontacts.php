<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191006090221Newscontacts
 */
class M191006090221Newscontacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'id_user_contact', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_news', 'id_user_contact');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191006090221Newscontacts cannot be reverted.\n";

        return false;
    }
    */
}
