<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191125111831AddIdUserGroupColumnInAuthEntity
 */
class M191125111831AddIdUserGroupColumnInAuthEntity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropPrimaryKey('auth_entity_pkey', 'auth_entity');

        $this->alterColumn('auth_entity', 'user_id', $this->integer());
        $this->addColumn('auth_entity', 'id', $this->primaryKey());
        $this->addColumn('auth_entity', 'id_user_group', $this->integer());

        $this->createIndex('idx-auth_entity_user_id_id_user_group_entity_id_class', 'auth_entity', ['user_id', 'id_user_group', 'entity_id', 'class'], true);
        $this->createIndex('idx-auth_entity-id_user_group', 'auth_entity', 'id_user_group');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('auth_entity_pkey', 'auth_entity');
        $this->dropIndex('idx-auth_entity_user_id_id_user_group_entity_id_class', 'auth_entity');

        $this->dropColumn('auth_entity', 'id');
        $this->dropColumn('auth_entity', 'id_user_group');
        $this->alterColumn('auth_entity', 'user_id', $this->integer()->notNull());

        $this->addPrimaryKey('auth_entity_pkey', 'auth_entity', ['user_id', 'entity_id', 'class']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191125111831AddIdUserGroupColumnInAuthEntity cannot be reverted.\n";

        return false;
    }
    */
}
