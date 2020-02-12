<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200212215705Collectintype
 */
class M200212215705Collectintype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('collection_type', [
            'id_type' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'is_faq'=> $this->boolean(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200212215705Collectintype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200212215705Collectintype cannot be reverted.\n";

        return false;
    }
    */
}
