<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class m190730_112309_cnt_page
 */
class m190730_112309_cnt_page extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('cnt_page', [
            'id_page' => $this->primaryKey(),
            'id_media' => $this->integer(),
            'title' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'content' => $this->text(),
            'seo_title' => $this->string(),
            'seo_description' => $this->string(),
            'seo_keywords' => $this->string(),
            'active' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('cnt_page');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190730_112309_cnt_page cannot be reverted.\n";

        return false;
    }
    */
}
