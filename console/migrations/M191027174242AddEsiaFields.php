<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191027174242AddEsiaFields
 */
class M191027174242AddEsiaFields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_esia_user', 'first_name', $this->string());
        $this->addColumn('auth_esia_user', 'last_name', $this->string());
        $this->addColumn('auth_esia_user', 'middle_name', $this->string());
        $this->addColumn('auth_esia_user', 'trusted', $this->string());

        $this->addColumn('auth_esia_user', 'home_phone', $this->string());
        $this->addColumn('auth_esia_user', 'living_addr', $this->string());
        $this->addColumn('auth_esia_user', 'living_addr_fias', $this->string());
        $this->addColumn('auth_esia_user', 'register_addr', $this->string());
        $this->addColumn('auth_esia_user', 'register_addr_fias', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191027174242AddEsiaFields cannot be reverted.\n";

        return false;
    }
    */
}
