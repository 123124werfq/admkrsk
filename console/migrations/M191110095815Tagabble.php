<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191110095815Tagabble
 */
class M191110095815Tagabble extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('db_tag', [
            'id' => $this->primaryKey(),
            'frequency' => $this->integer(),
            'name' => $this->string(),
        ]);

        $this->createTable('dbl_news_tag', [
            'id_tag' => $this->integer(),
            'id_news' => $this->integer(),
        ]);

        $this->addPrimaryKey('dbl_new_tag_pk', 'dbl_news_tag', ['id_tag', 'id_news']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_tag');

        $this->dropTable('dbl_news_tag');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191110095815Tagabble cannot be reverted.\n";

        return false;
    }
    */
}
