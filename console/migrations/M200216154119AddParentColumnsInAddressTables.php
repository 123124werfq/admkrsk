<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200216154119AddParentColumnsInAddressTables
 */
class M200216154119AddParentColumnsInAddressTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('map_region', 'id_country', $this->integer());
        $this->addColumn('map_subregion', 'id_region', $this->integer());
        $this->addColumn('map_city', 'id_region', $this->integer());
        $this->addColumn('map_city', 'id_subregion', $this->integer());
        $this->addColumn('map_district', 'id_city', $this->integer());
        $this->addColumn('map_street', 'id_city', $this->integer());

        $this->createIndex('idx-map_region-id_country', 'map_region', 'id_country');
        $this->createIndex('idx-map_subregion-id_region', 'map_subregion', 'id_region');
        $this->createIndex('idx-map_city-id_region', 'map_city', 'id_region');
        $this->createIndex('idx-map_city-id_subregion', 'map_city', 'id_subregion');
        $this->createIndex('idx-map_district-id_city', 'map_district', 'id_city');
        $this->createIndex('idx-map_street-id_city', 'map_street', 'id_city');

        $this->addForeignKey('fk-map_region-id_country-map_country-id_country', 'map_region', 'id_country', 'map_country', 'id_country', 'SET NULL');
        $this->addForeignKey('fk-map_subregion-id_region-map_region-id_region', 'map_subregion', 'id_region', 'map_region', 'id_region', 'SET NULL');
        $this->addForeignKey('fk-map_city-id_region-map_region-id_region', 'map_city', 'id_region', 'map_region', 'id_region', 'SET NULL');
        $this->addForeignKey('fk-map_city-id_subregion-map_subregion-id_subregion', 'map_city', 'id_subregion', 'map_subregion', 'id_subregion', 'SET NULL');
        $this->addForeignKey('fk-map_district-id_city-map_city-id_city', 'map_district', 'id_city', 'map_city', 'id_city', 'SET NULL');
        $this->addForeignKey('fk-map_street-id_city-map_city-id_city', 'map_street', 'id_city', 'map_city', 'id_city', 'SET NULL');

        $this->createTable('mapl_street_district', [
            'id_street' => $this->integer()->notNull(),
            'id_district' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-mapl_street_district-id_street', 'mapl_street_district', 'id_street');
        $this->createIndex('idx-mapl_street_district-id_district', 'mapl_street_district', 'id_district');

        $this->addForeignKey('fk-mapl_street_district-id_street-map_street-id_street', 'mapl_street_district', 'id_street', 'map_street', 'id_street', 'CASCADE');
        $this->addForeignKey('fk-mapl_street_district-id_district-map_district-id_district', 'mapl_street_district', 'id_district', 'map_district', 'id_district', 'CASCADE');

        $this->createIndex('idx-map_house-id_country', 'map_house', 'id_country');
        $this->createIndex('idx-map_house-id_region', 'map_house', 'id_region');
        $this->createIndex('idx-map_house-id_subregion', 'map_house', 'id_subregion');
        $this->createIndex('idx-map_house-id_city', 'map_house', 'id_city');
        $this->createIndex('idx-map_house-id_district', 'map_house', 'id_district');
        $this->createIndex('idx-map_house-id_street', 'map_house', 'id_street');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        $this->dropForeignKey('fk-map_region-id_country-map_country-id_country', 'map_region');
        $this->dropForeignKey('fk-map_subregion-id_region-map_region-id_region', 'map_subregion');
        $this->dropForeignKey('fk-map_city-id_region-map_region-id_region', 'map_city');
        $this->dropForeignKey('fk-map_city-id_subregion-map_subregion-id_subregion', 'map_city');
        $this->dropForeignKey('fk-map_district-id_city-map_city-id_city', 'map_district');
        $this->dropForeignKey('fk-map_street-id_city-map_city-id_city', 'map_street');

        $this->dropColumn('map_region', 'id_country');
        $this->dropColumn('map_subregion', 'id_region');
        $this->dropColumn('map_city', 'id_region');
        $this->dropColumn('map_city', 'id_subregion');
        $this->dropColumn('map_district', 'id_city');
        $this->dropColumn('map_street', 'id_city');

        $this->dropForeignKey('fk-mapl_street_district-id_street-map_street-id_street', 'mapl_street_district');
        $this->dropForeignKey('fk-mapl_street_district-id_district-map_district-id_district', 'mapl_street_district');

        $this->dropTable('mapl_street_district');

        $this->dropIndex('idx-map_house-id_country', 'map_house');
        $this->dropIndex('idx-map_house-id_region', 'map_house');
        $this->dropIndex('idx-map_house-id_subregion', 'map_house');
        $this->dropIndex('idx-map_house-id_city', 'map_house');
        $this->dropIndex('idx-map_house-id_district', 'map_house');
        $this->dropIndex('idx-map_house-id_street', 'map_house');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200216154119AddParentColumnsInAddressTables cannot be reverted.\n";

        return false;
    }
    */
}
