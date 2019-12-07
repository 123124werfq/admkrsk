<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191206172852Groups
 */
class M191206172852Groups extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'id_group', $this->integer());
        $this->addColumn('db_collection', 'id_group', $this->integer());
        $this->addColumn('db_collection', 'system', $this->integer());
        $this->addColumn('form_form', 'system', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191206172852Groups cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191206172852Groups cannot be reverted.\n";

        return false;
    }
    */
}
