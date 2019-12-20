<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191219224111AddIsManualColumnToMapTables
 */
class M191219224111AddIsManualColumnToMapTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('map_country', 'update_at', $this->integer());
        $this->addColumn('map_country', 'created_by', $this->integer());
        $this->addColumn('map_country', 'updated_at', $this->integer());
        $this->addColumn('map_country', 'updated_by', $this->integer());
        $this->addColumn('map_country', 'deleted_at', $this->integer());
        $this->addColumn('map_country', 'deleted_by', $this->integer());

        $this->addColumn('map_region', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_region', 'update_at', $this->integer());
        $this->addColumn('map_region', 'created_by', $this->integer());
        $this->addColumn('map_region', 'updated_at', $this->integer());
        $this->addColumn('map_region', 'updated_by', $this->integer());
        $this->addColumn('map_region', 'deleted_at', $this->integer());
        $this->addColumn('map_region', 'deleted_by', $this->integer());

        $this->addColumn('map_subregion', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_subregion', 'update_at', $this->integer());
        $this->addColumn('map_subregion', 'created_by', $this->integer());
        $this->addColumn('map_subregion', 'updated_at', $this->integer());
        $this->addColumn('map_subregion', 'updated_by', $this->integer());
        $this->addColumn('map_subregion', 'deleted_at', $this->integer());
        $this->addColumn('map_subregion', 'deleted_by', $this->integer());

        $this->addColumn('map_city', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_city', 'update_at', $this->integer());
        $this->addColumn('map_city', 'created_by', $this->integer());
        $this->addColumn('map_city', 'updated_at', $this->integer());
        $this->addColumn('map_city', 'updated_by', $this->integer());
        $this->addColumn('map_city', 'deleted_at', $this->integer());
        $this->addColumn('map_city', 'deleted_by', $this->integer());

        $this->addColumn('map_district', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_district', 'update_at', $this->integer());
        $this->addColumn('map_district', 'created_by', $this->integer());
        $this->addColumn('map_district', 'updated_at', $this->integer());
        $this->addColumn('map_district', 'updated_by', $this->integer());
        $this->addColumn('map_district', 'deleted_at', $this->integer());
        $this->addColumn('map_district', 'deleted_by', $this->integer());

        $this->addColumn('map_street', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_street', 'update_at', $this->integer());
        $this->addColumn('map_street', 'created_by', $this->integer());
        $this->addColumn('map_street', 'updated_at', $this->integer());
        $this->addColumn('map_street', 'updated_by', $this->integer());
        $this->addColumn('map_street', 'deleted_at', $this->integer());
        $this->addColumn('map_street', 'deleted_by', $this->integer());

        $this->addColumn('map_house', 'is_manual', $this->boolean()->defaultValue(false));
        $this->addColumn('map_house', 'update_at', $this->integer());
        $this->addColumn('map_house', 'created_by', $this->integer());
        $this->addColumn('map_house', 'updated_at', $this->integer());
        $this->addColumn('map_house', 'updated_by', $this->integer());
        $this->addColumn('map_house', 'deleted_at', $this->integer());
        $this->addColumn('map_house', 'deleted_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('map_country', 'update_at');
        $this->dropColumn('map_country', 'created_by');
        $this->dropColumn('map_country', 'updated_at');
        $this->dropColumn('map_country', 'updated_by');
        $this->dropColumn('map_country', 'deleted_at');
        $this->dropColumn('map_country', 'deleted_by');

        $this->dropColumn('map_region', 'is_manual');
        $this->dropColumn('map_region', 'update_at');
        $this->dropColumn('map_region', 'created_by');
        $this->dropColumn('map_region', 'updated_at');
        $this->dropColumn('map_region', 'updated_by');
        $this->dropColumn('map_region', 'deleted_at');
        $this->dropColumn('map_region', 'deleted_by');

        $this->dropColumn('map_subregion', 'is_manual');
        $this->dropColumn('map_subregion', 'update_at');
        $this->dropColumn('map_subregion', 'created_by');
        $this->dropColumn('map_subregion', 'updated_at');
        $this->dropColumn('map_subregion', 'updated_by');
        $this->dropColumn('map_subregion', 'deleted_at');
        $this->dropColumn('map_subregion', 'deleted_by');

        $this->dropColumn('map_city', 'is_manual');
        $this->dropColumn('map_city', 'update_at');
        $this->dropColumn('map_city', 'created_by');
        $this->dropColumn('map_city', 'updated_at');
        $this->dropColumn('map_city', 'updated_by');
        $this->dropColumn('map_city', 'deleted_at');
        $this->dropColumn('map_city', 'deleted_by');

        $this->dropColumn('map_district', 'is_manual');
        $this->dropColumn('map_district', 'update_at');
        $this->dropColumn('map_district', 'created_by');
        $this->dropColumn('map_district', 'updated_at');
        $this->dropColumn('map_district', 'updated_by');
        $this->dropColumn('map_district', 'deleted_at');
        $this->dropColumn('map_district', 'deleted_by');

        $this->dropColumn('map_street', 'is_manual');
        $this->dropColumn('map_street', 'update_at');
        $this->dropColumn('map_street', 'created_by');
        $this->dropColumn('map_street', 'updated_at');
        $this->dropColumn('map_street', 'updated_by');
        $this->dropColumn('map_street', 'deleted_at');
        $this->dropColumn('map_street', 'deleted_by');

        $this->dropColumn('map_house', 'is_manual');
        $this->dropColumn('map_house', 'update_at');
        $this->dropColumn('map_house', 'created_by');
        $this->dropColumn('map_house', 'updated_at');
        $this->dropColumn('map_house', 'updated_by');
        $this->dropColumn('map_house', 'deleted_at');
        $this->dropColumn('map_house', 'deleted_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191219224111AddIsManualColumnToMapTables cannot be reverted.\n";

        return false;
    }
    */
}
