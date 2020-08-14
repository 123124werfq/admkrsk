<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200813230523CreateMapPlaceTable
 */
class M200813230523CreateMapPlaceTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%map_place}}', [
            'id_place' => $this->primaryKey(),
            'id_house' => $this->integer(),
            'name' => $this->string(),
            'lat' => $this->decimal(18,15),
            'lon' => $this->decimal(18,15),
            'update_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk-map_place-id_house-map_house-id_house', 'map_place', 'id_house', 'map_house', 'id_house', 'SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-map_place-id_house-map_house-id_house', 'map_place');

        $this->dropTable('{{%map_place}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200813230523CreateMapPlaceTable cannot be reverted.\n";

        return false;
    }
    */
}
