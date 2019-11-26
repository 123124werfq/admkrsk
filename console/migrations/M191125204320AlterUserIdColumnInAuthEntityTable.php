<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191125204320AlterUserIdColumnInAuthEntityTable
 */
class M191125204320AlterUserIdColumnInAuthEntityTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropIndex('idx-auth_entity-user_id', 'auth_entity');
        $this->dropIndex('idx-auth_entity_user_id_id_user_group_entity_id_class', 'auth_entity');

        $this->renameColumn('auth_entity', 'user_id', 'id_user');

        $this->createIndex('idx-auth_entity-id_user', 'auth_entity', 'id_user');
        $this->createIndex('idx-auth_entity-id_user_id_user_group_entity_id_class', 'auth_entity', ['id_user', 'id_user_group', 'entity_id', 'class'], true);

        $this->addForeignKey('fk-auth_entity-id_user-user-id', 'auth_entity', 'id_user', 'user', 'id', 'CASCADE');
        $this->addForeignKey('fk-auth_entity-id_user_group-db_user_group-id_user_group', 'auth_entity', 'id_user_group', 'db_user_group', 'id_user_group', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-auth_entity-id_user-user-id', 'auth_entity', 'id_user');
        $this->dropForeignKey('fk-auth_entity-id_user_group-db_user_group-id_user_group', 'auth_entity', 'id_user_group');

        $this->dropIndex('idx-auth_entity-id_user', 'auth_entity');
        $this->dropIndex('idx-auth_entity-id_user_id_user_group_entity_id_class', 'auth_entity');

        $this->renameColumn('auth_entity', 'id_user', 'user_id');

        $this->createIndex('idx-auth_entity-user_id', 'auth_entity', 'user_id');
        $this->createIndex('idx-auth_entity_user_id_id_user_group_entity_id_class', 'auth_entity', ['user_id', 'id_user_group', 'entity_id', 'class'], true);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191125204320AlterUserIdColumnInAuthEntityTable cannot be reverted.\n";

        return false;
    }
    */
}
