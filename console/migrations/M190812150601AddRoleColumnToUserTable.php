<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190812150601AddRoleColumnToUserTable
 */
class M190812150601AddRoleColumnToUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(32));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'role');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190812150601AddRoleColumnToUserTable cannot be reverted.\n";

        return false;
    }
    */
}
