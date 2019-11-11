<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191105201949Useraddfields
 */
class M191105201949Useraddfields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'description', $this->text());
        $this->addColumn('user', 'phone', $this->string());
        $this->addColumn('user', 'id_media', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191105201949Useraddfields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191105201949Useraddfields cannot be reverted.\n";

        return false;
    }
    */
}
