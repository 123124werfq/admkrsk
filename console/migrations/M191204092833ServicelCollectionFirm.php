<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191204092833ServicelCollectionFirm
 */
class M191204092833ServicelCollectionFirm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
         $this->createTable('servicel_collection_firm', [
            'id_service' => $this->integer(),
            'id_record' => $this->integer(),
        ]);

         $this->addPrimaryKey('servicel_collection_firm_pk', 'servicel_collection_firm', ['id_service', 'id_record']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191204092833ServicelCollectionFirm cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191204092833ServicelCollectionFirm cannot be reverted.\n";

        return false;
    }
    */
}
