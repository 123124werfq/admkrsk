<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191126220544Newscontact
 */
class M191126220544Newscontact extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'id_record_contact', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191126220544Newscontact cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191126220544Newscontact cannot be reverted.\n";

        return false;
    }
    */
}
