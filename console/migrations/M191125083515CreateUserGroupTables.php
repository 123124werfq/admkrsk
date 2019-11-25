<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191125083515CreateUserGroupTables
 */
class M191125083515CreateUserGroupTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_user_group', [
            'id_user_group' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('dbl_user_user_group', [
            'id_user' => $this->integer()->notNull(),
            'id_user_group' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-dbl_user_user_group-id_user-user-id', 'dbl_user_user_group', 'id_user', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-dbl_user_user_group-id_user_group-dbl_user_user_group-id_user_group', 'dbl_user_user_group', 'id_user_group', 'db_user_group', 'id_user_group', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-dbl_user_user_group-id_user-user-id', 'dbl_user_user_group');
        $this->dropForeignKey('fk-dbl_user_user_group-id_user_group-dbl_user_user_group-id_user_group', 'dbl_user_user_group');

        $this->dropTable('db_user_group');
        $this->dropTable('dbl_user_user_group');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191125083515CreateUserGroupTable cannot be reverted.\n";

        return false;
    }
    */
}
