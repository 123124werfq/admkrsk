<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190923225927CreateAuthEntityTable
 */
class M190923225927CreateAuthEntityTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%auth_entity}}', [
            'user_id' => $this->integer()->notNull(),
            'entity_id' => $this->integer()->notNull(),
            'class' => $this->string(),
            'PRIMARY KEY ([[user_id]], [[entity_id]], [[class]])',
        ]);

        $this->createIndex('idx-auth_entity-user_id', '{{%auth_entity}}', 'user_id');
        $this->createIndex('idx-auth_entity-entity_id', '{{%auth_entity}}', 'entity_id');
        $this->createIndex('idx-auth_entity-class', '{{%auth_entity}}', 'class');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth_entity}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190923225927CreateAuthEntityTable cannot be reverted.\n";

        return false;
    }
    */
}
