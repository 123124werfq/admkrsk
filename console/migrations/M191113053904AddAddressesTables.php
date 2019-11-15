<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191113053904AddAddressesTables
 */
class M191113053904AddAddressesTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('map_region', [
            'id_region' => $this->primaryKey(),
            'aoguid' => 'uuid',
            'name' => $this->string(),
        ]);

        $this->createTable('map_subregion', [
            'id_subregion' => $this->primaryKey(),
            'aoguid' => 'uuid',
            'name' => $this->string(),
        ]);

        $this->createTable('map_city', [
            'id_city' => $this->primaryKey(),
            'aoguid' => 'uuid',
            'name' => $this->string(),
        ]);

        $this->createTable('map_district', [
            'id_district' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $this->createTable('map_street', [
            'id_street' => $this->primaryKey(),
            'aoguid' => 'uuid',
            'name' => $this->string(),
        ]);

        $this->createTable('map_house', [
            'id_house' => $this->primaryKey(),
            'id_region' => $this->integer(),
            'id_subregion' => $this->integer(),
            'id_city' => $this->integer(),
            'id_district' => $this->integer(),
            'id_street' => $this->integer(),
            'houseguid' => 'uuid',
            'postalcode' => $this->string(),
            'name' => $this->string(),
            'fullname' => $this->string(),
        ]);

        $this->dropForeignKey('fk-db_address-houseguid-fias_house-houseguid', 'db_address');

        $this->dropTable('db_address');

        $this->addForeignKey('fk-map_house-id_region-map_region-id_region', 'map_house', 'id_region', 'map_region', 'id_region');
        $this->addForeignKey('fk-map_house-id_subregion-map_subregion-id_subregion', 'map_house', 'id_subregion', 'map_subregion', 'id_subregion');
        $this->addForeignKey('fk-map_house-id_city-map_city-id_city', 'map_house', 'id_city', 'map_city', 'id_city');
        $this->addForeignKey('fk-map_house-id_district-map_district-id_district', 'map_house', 'id_district', 'map_district', 'id_district');
        $this->addForeignKey('fk-map_house-id_street-map_street-id_street', 'map_house', 'id_street', 'map_street', 'id_street');
        $this->addForeignKey('fk-map_house-id_house-map_house-id_house', 'map_house', 'id_house', 'map_house', 'id_house');
        $this->addForeignKey('fk-map_house-houseguid-fias_house-houseguid', 'map_house', 'houseguid', 'fias_house', 'houseguid', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-map_house-id_region-map_region-id_region', 'map_house');
        $this->dropForeignKey('fk-map_house-id_subregion-map_subregion-id_subregion', 'map_house');
        $this->dropForeignKey('fk-map_house-id_city-map_city-id_city', 'map_house');
        $this->dropForeignKey('fk-map_house-id_district-map_district-id_district', 'map_house');
        $this->dropForeignKey('fk-map_house-id_street-map_street-id_street', 'map_house');
        $this->dropForeignKey('fk-map_house-id_house-map_house-id_house', 'map_house');
        $this->dropForeignKey('fk-map_house-houseguid-fias_house-houseguid', 'map_house');

        $this->dropTable('map_region');
        $this->dropTable('map_subregion');
        $this->dropTable('map_city');
        $this->dropTable('map_district');
        $this->dropTable('map_street');
        $this->dropTable('map_house');

        $this->createTable('db_address', [
            'id' => $this->primaryKey(),
            'houseguid' => 'uuid',
            'address' => $this->string(),
        ]);

        $this->addForeignKey('fk-db_address-houseguid-fias_house-houseguid', 'db_address', 'houseguid', 'fias_house', 'houseguid', 'CASCADE');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191113053904AddAddressesTables cannot be reverted.\n";

        return false;
    }
    */
}
