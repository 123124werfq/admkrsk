<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191106153217CreateAddressTable
 */
class M191106153217CreateAddressTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_address', [
            'id' => $this->primaryKey(),
            'houseguid' => 'uuid',
            'address' => $this->string(),
        ]);

        $this->addForeignKey('fk-db_address-houseguid-fias_house-houseguid', 'db_address', 'houseguid', 'fias_house', 'houseguid', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-db_address-houseguid-fias_house-houseguid', 'db_address');

        $this->dropTable('db_address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191106153217CreateAddressTable cannot be reverted.\n";

        return false;
    }
    */
}
