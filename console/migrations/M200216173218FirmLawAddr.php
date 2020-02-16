<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200216173218FirmLawAddr
 */
class M200216173218FirmLawAddr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_esia_firm', 'law_addr', $this->string());
        $this->addColumn('auth_esia_firm', 'law_addr_fias', $this->string());
        $this->addColumn('auth_esia_firm', 'law_addr_fias_alt', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('auth_esia_firm', 'law_addr');
        $this->dropColumn('auth_esia_firm', 'law_addr_fias');
        $this->dropColumn('auth_esia_firm', 'law_addr_fias_alt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200216173218FirmLawAddr cannot be reverted.\n";

        return false;
    }
    */
}
