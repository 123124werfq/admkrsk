<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190915191409Menus
 */
class M190915191409Menus extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_menu', [
            'id_menu' => $this->primaryKey(),
            'id_page' => $this->integer(),
            'alias' => $this->string(),
            'name' => $this->string(255)->notNull(),
            'state' => $this->integer(),
            'type' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_menu_link', [
            'id_link' => $this->primaryKey(),
            'id_parent' => $this->integer(),
            'id_menu' => $this->integer(),
            'id_media' => $this->integer(),
            'id_page' => $this->integer(),
            'label' => $this->string(255)->notNull(),
            'url' => $this->string(),
            'content'=> $this->text(),
            'state'=> $this->integer(),
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
        $this->dropTable('db_menu');
        $this->dropTable('db_menu_link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190915191409Menus cannot be reverted.\n";

        return false;
    }
    */
}
