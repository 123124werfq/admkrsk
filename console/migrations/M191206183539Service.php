<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191206183539Service
 */
class M191206183539Service extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'id_service', $this->integer());
        $this->addColumn('form_form', 'fullname', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191206183539Service cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191206183539Service cannot be reverted.\n";

        return false;
    }
    */
}
