<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191214080959Addcolumns
 */
class M191214080959Addcolumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'alias', $this->string());
        $this->addColumn('form_input', 'id_collection_column', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191214080959Addcolumns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191214080959Addcolumns cannot be reverted.\n";

        return false;
    }
    */
}
