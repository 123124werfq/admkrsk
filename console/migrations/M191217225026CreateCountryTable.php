<?php

namespace console\migrations;

use common\models\Country;
use common\models\House;
use yii\db\Migration;

/**
 * Class M191217225026CreateCountryTable
 */
class M191217225026CreateCountryTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('map_country', [
            'id_country' => $this->primaryKey(),
            'name' => $this->string(),
        ]);

        $country = new Country(['name' => 'Россия']);
        $country->save();

        $this->addColumn('map_house', 'id_country', $this->integer());

        House::updateAll(['id_country' => $country->id_country]);

        $this->addForeignKey('fk-map_house-id_country-map_country-id_region', 'map_house', 'id_country', 'map_country', 'id_country');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-map_house-id_country-map_country-id_region', 'map_house');

        $this->dropColumn('map_house', 'id_country');

        $this->dropTable('map_country');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191217225026CreateCountryTable cannot be reverted.\n";

        return false;
    }
    */
}
