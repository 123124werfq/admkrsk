<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200113222623Partitionrelations
 */
class M200113222623Partitionrelations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dbl_gallery_page', [
            'id_page' => $this->integer(),
            'id_gallery' => $this->integer(),
        ]);

        $this->addPrimaryKey('dbl_gallery_page_pk', 'dbl_gallery_page', ['id_page', 'id_gallery']);

        $this->createTable('dbl_form_page', [
            'id_page' => $this->integer(),
            'id_form' => $this->integer(),
        ]);

        $this->addPrimaryKey('dbl_form_page_pk', 'dbl_form_page', ['id_page', 'id_form']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200113222623Partitionrelations cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200113222623Partitionrelations cannot be reverted.\n";

        return false;
    }
    */
}
