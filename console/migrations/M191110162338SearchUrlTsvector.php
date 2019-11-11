<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191110162338SearchUrlTsvector
 */
class M191110162338SearchUrlTsvector extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('search_sitemap', 'url');
        $this->addColumn('search_sitemap', 'url', $this->string());
        $this->addColumn('search_sitemap', 'tsvector', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "M191110162338SearchUrlTsvector cannot be reverted.\n";
        $this->dropColumn('search_sitemap', 'tsvector');

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191110162338SearchUrlTsvector cannot be reverted.\n";

        return false;
    }
    */
}
