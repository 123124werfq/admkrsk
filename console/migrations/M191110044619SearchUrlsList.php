<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191110044619SearchUrlsList
 */
class M191110044619SearchUrlsList extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
       $this->createTable('search_sitemap', [
            'id_sitemap' => $this->primaryKey(),
            'url' => $this->integer(),
            'content' => $this->text(),
            'content_date' => $this->string(),
            'active' => $this->integer()->defaultValue(1),

            'modified_at' => $this->integer(),
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
        $this->dropTable('search_sitemap');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191110044619SearchUrlsList cannot be reverted.\n";

        return false;
    }
    */
}
