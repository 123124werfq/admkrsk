<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200114123120AddCoordinatesColumnsToHouseTable
 */
class M200114123120AddCoordinatesColumnsToHouseTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('map_house', 'lat', $this->decimal(15,12));
        $this->addColumn('map_house', 'lon', $this->decimal(15,12));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('map_house', 'lat');
        $this->dropColumn('map_house', 'lon');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200114123120AddCoordinatesColumnsToHouseTable cannot be reverted.\n";

        return false;
    }
    */
}
