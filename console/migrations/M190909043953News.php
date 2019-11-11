<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190909043953News
 */
class M190909043953News extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_news', [
            'id_news' => $this->primaryKey(),
            'id_page' => $this->integer(),
            'id_category' => $this->integer(),
            'id_rub' => $this->integer(),
            'id_media' => $this->integer(),
            'title' => $this->string()->notNull(),
            'description' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'date_publish' => $this->integer(),
            'date_unpublish' => $this->integer(),
            'state' => $this->integer(),
            'main' => $this->integer(),
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
        $this->dropTable('db_news');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190909043953News cannot be reverted.\n";

        return false;
    }
    */
}
