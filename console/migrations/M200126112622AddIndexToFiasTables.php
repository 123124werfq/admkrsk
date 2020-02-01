<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200126112622AddIndexToFiasTables
 */
class M200126112622AddIndexToFiasTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-fias_addrobj-aoguid', 'fias_addrobj', 'aoguid');
        $this->createIndex('idx-fias_house-houseguid', 'fias_house', 'houseguid');

        $this->createIndex('idx-map_city-aoguid', 'map_city', 'aoguid');
        $this->createIndex('idx-map_region-aoguid', 'map_region', 'aoguid');
        $this->createIndex('idx-map_street-aoguid', 'map_street', 'aoguid');
        $this->createIndex('idx-map_subregion-aoguid', 'map_subregion', 'aoguid');
        $this->createIndex('idx-map_house-houseguid', 'map_house', 'houseguid');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-fias_addrobj-aoguid', 'fias_addrobj');
        $this->dropIndex('idx-fias_house-houseguid', 'fias_house');

        $this->dropIndex('idx-map_city-aoguid', 'map_city');
        $this->dropIndex('idx-map_region-aoguid', 'map_region');
        $this->dropIndex('idx-map_street-aoguid', 'map_street');
        $this->dropIndex('idx-map_subregion-aoguid', 'map_subregion');
        $this->dropIndex('idx-map_house-houseguid', 'map_house');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200126112622AddIndexToFiasTables cannot be reverted.\n";

        return false;
    }
    */
}
