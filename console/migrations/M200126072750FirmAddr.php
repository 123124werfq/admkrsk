<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200126072750FirmAddr
 */
class M200126072750FirmAddr extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_esia_firm', 'main_addr', $this->string());
        $this->addColumn('auth_esia_firm', 'main_addr_fias', $this->string());
        $this->addColumn('auth_esia_firm', 'main_addr_fias_alt', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('auth_esia_firm', 'main_addr');
        $this->dropColumn('auth_esia_firm', 'main_addr_fias');
        $this->dropColumn('auth_esia_firm', 'main_addr_fias_alt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200126072750FirmAddr cannot be reverted.\n";

        return false;
    }
    */
}
