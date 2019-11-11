<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190923071916Gallery
 */
class M190923071916Gallery extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_gallery', [
            'id_gallery' => $this->primaryKey(),
            'id_page' => $this->integer(),
            'name' => $this->string(255)->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('dbl_gallery_media', [
            'id_gallery' => $this->integer()->notNull(),
            'id_media' => $this->integer()->notNull(),
            'ord' => $this->integer(),
        ]);

        $this->addPrimaryKey('dbl_gallery_media_pk', 'dbl_gallery_media', ['id_gallery', 'id_media']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190923071916Gallery cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190923071916Gallery cannot be reverted.\n";

        return false;
    }
    */
}
