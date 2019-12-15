<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191215080147AddUrlsColumnInOpendataTable
 */
class M191215080147AddUrlsColumnInOpendataTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-db_opendata-id_page-cnt_page-id_page', 'db_opendata');

        $this->dropColumn('db_opendata', 'id_page');

        $this->addColumn('db_opendata', 'urls', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_opendata', 'urls');

        $this->addColumn('db_opendata', 'id_page', $this->integer());

        $this->addForeignKey('fk-db_opendata-id_page-cnt_page-id_page', 'db_opendata', 'id_page', 'cnt_page', 'id_page', 'SET NULL');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191215080147AddUrlsColumnInOpendataTable cannot be reverted.\n";

        return false;
    }
    */
}
