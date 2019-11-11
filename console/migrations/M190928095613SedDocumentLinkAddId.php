<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190928095613SedDocumentLinkAddId
 */
class M190928095613SedDocumentLinkAddId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_pdocument_link', 'id_link', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190928095613SedDocumentLinkAddId cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190928095613SedDocumentLinkAddId cannot be reverted.\n";

        return false;
    }
    */
}
