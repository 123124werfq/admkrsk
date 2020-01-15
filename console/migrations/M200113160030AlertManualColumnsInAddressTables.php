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
 * Class M200113160030AlertManualColumnsInAddressTables
 */
class M200113160030AlertManualColumnsInAddressTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('map_region', 'is_manual', 'is_updatable');
        $this->renameColumn('map_subregion', 'is_manual', 'is_updatable');
        $this->renameColumn('map_city', 'is_manual', 'is_updatable');
        $this->renameColumn('map_district', 'is_manual', 'is_updatable');
        $this->renameColumn('map_street', 'is_manual', 'is_updatable');
        $this->renameColumn('map_house', 'is_manual', 'is_updatable');

        Region::updateAll(['is_updatable' => true]);
        Subregion::updateAll(['is_updatable' => true]);
        City::updateAll(['is_updatable' => true]);
        District::updateAll(['is_updatable' => true]);
        Street::updateAll(['is_updatable' => true]);
        House::updateAll(['is_updatable' => true]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('map_region', 'is_updatable', 'is_manual');
        $this->renameColumn('map_subregion', 'is_updatable', 'is_manual');
        $this->renameColumn('map_city', 'is_updatable', 'is_manual');
        $this->renameColumn('map_district', 'is_updatable', 'is_manual');
        $this->renameColumn('map_street', 'is_updatable', 'is_manual');
        $this->renameColumn('map_house', 'is_updatable', 'is_manual');

        Region::updateAll(['is_manual' => false]);
        Subregion::updateAll(['is_manual' => false]);
        City::updateAll(['is_manual' => false]);
        District::updateAll(['is_manual' => false]);
        Street::updateAll(['is_manual' => false]);
        House::updateAll(['is_manual' => false]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200113160030AlertManualColumnsInAddressTables cannot be reverted.\n";

        return false;
    }
    */
}
