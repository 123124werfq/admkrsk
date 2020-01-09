<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200103184354Pagetopartitional
 */
class M200103184354Pagetopartitional extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'is_partition', $this->boolean()->defaultValue(false));
        $this->addColumn('db_block', 'id_page_layout', $this->integer());

        $this->createTable('dbl_collection_page', [
            'id_page' => $this->integer(),
            'id_collection' => $this->integer(),
        ]);

        $this->addPrimaryKey('dbl_collection_page_pk', 'dbl_collection_page', ['id_page', 'id_collection']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200103184354Pagetopartitional cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200103184354Pagetopartitional cannot be reverted.\n";

        return false;
    }
    */
}
