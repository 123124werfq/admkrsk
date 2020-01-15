<?php

namespace console\migrations;

use common\models\City;
use common\models\District;
use common\models\House;
use common\models\Region;
use common\models\Street;
use common\models\Subregion;
use yii\db\Migration;

/**
 * Class M200115025015AddIsActiveColumnsInAddressTables
 */
class M200115025015AddIsActiveColumnsInAddressTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('map_region', 'is_active', $this->boolean()->defaultValue(false));
        $this->addColumn('map_subregion', 'is_active', $this->boolean()->defaultValue(false));
        $this->addColumn('map_city', 'is_active', $this->boolean()->defaultValue(false));
        $this->addColumn('map_district', 'is_active', $this->boolean()->defaultValue(false));
        $this->addColumn('map_street', 'is_active', $this->boolean()->defaultValue(false));
        $this->addColumn('map_house', 'is_active', $this->boolean()->defaultValue(false));

        Region::updateAll(['is_active' => true]);
        Subregion::updateAll(['is_active' => true]);
        City::updateAll(['is_active' => true]);
        District::updateAll(['is_active' => true]);
        Street::updateAll(['is_active' => true]);
        House::updateAll(['is_active' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('map_region', 'is_active');
        $this->dropColumn('map_subregion', 'is_active');
        $this->dropColumn('map_city', 'is_active');
        $this->dropColumn('map_district', 'is_active');
        $this->dropColumn('map_street', 'is_active');
        $this->dropColumn('map_house', 'is_active');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200115025015AddIsActiveColumnsInAddressTables cannot be reverted.\n";

        return false;
    }
    */
}
