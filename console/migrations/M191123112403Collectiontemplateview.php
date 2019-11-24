<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191123112403Collectiontemplateview
 */
class M191123112403Collectiontemplateview extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'template_element', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191123112403Collectiontemplateview cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191123112403Collectiontemplateview cannot be reverted.\n";

        return false;
    }
    */
}
