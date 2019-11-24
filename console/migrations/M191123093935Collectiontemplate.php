<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191123093935Collectiontemplate
 */
class M191123093935Collectiontemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_row', 'options', $this->json());
        $this->addColumn('db_collection', 'template', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191123093935Collectiontemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191123093935Collectiontemplate cannot be reverted.\n";

        return false;
    }
    */
}
