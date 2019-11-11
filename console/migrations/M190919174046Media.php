<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190919174046Media
 */
class M190919174046Media extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('cnt_media', [
            'id_media' => $this->primaryKey(),
            'type' => $this->integer(),
            'size' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'width' => $this->integer(),
            'height' => $this->integer(),
            'duration' => $this->integer(),
            'mime' => $this->string(255),
            'extension' => $this->string(255),
            'ord' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cnt_media');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190919174046Media cannot be reverted.\n";

        return false;
    }
    */
}
