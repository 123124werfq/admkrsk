<?php

namespace console\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M200107124248RecreateFiasTables
 */
class M200107124248RecreateFiasTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (isset(Yii::$app->db->getTableSchema('map_house')->foreignKeys['fk-map_house-houseguid-fias_house-houseguid'])) {
            $this->dropForeignKey('fk-map_house-houseguid-fias_house-houseguid', 'map_house');
        }

        if (in_array('fias_house', Yii::$app->db->schema->tableNames)) {
            $this->dropTable('fias_house');
        }

        if (in_array('fias_addrobj', Yii::$app->db->schema->tableNames)) {
            $this->dropTable('fias_addrobj');
        }

        $this->createTable('fias_addrobj', [
            'id' => $this->primaryKey(),
            'aoguid' => 'uuid',
            'formalname' => $this->string(120),
            'regioncode' => $this->string(2),
            'autocode' => $this->string(1),
            'areacode' => $this->string(3),
            'citycode' => $this->string(3),
            'ctarcode' => $this->string(3),
            'placecode' => $this->string(3),
            'streetcode' => $this->string(4),
            'extrcode' => $this->string(4),
            'sextcode' => $this->string(3),
            'plancode' => $this->string(4),
            'cadnum' => $this->string(100),
            'divtype' => $this->integer(),
            'offname' => $this->string(120),
            'postalcode' => $this->string(6),
            'ifnsfl' => $this->string(4),
            'terrifnsfl' => $this->string(4),
            'ifnsul' => $this->string(4),
            'terrifnsul' => $this->string(4),
            'okato' => $this->string(11),
            'oktmo' => $this->string(11),
            'updatedate' => $this->dateTime(),
            'shortname' => $this->string(10),
            'aolevel' => $this->integer(),
            'parentguid' => 'uuid',
            'aoid' => 'uuid',
            'previd' => 'uuid',
            'nextid' => 'uuid',
            'code' => $this->string(17),
            'plaincode' => $this->string(15),
            'actstatus' => $this->integer(),
            'centstatus' => $this->integer(),
            'operstatus' => $this->integer(),
            'currstatus' => $this->integer(),
            'livestatus' => $this->integer(),
            'startdate' => $this->dateTime(),
            'enddate' => $this->dateTime(),
            'normdoc' => 'uuid',
        ]);

        $this->createTable('fias_house', [
            'id' => $this->primaryKey(),
            'postalcode' => $this->string(6),
            'ifnsfl' => $this->string(4),
            'terrifnsfl' => $this->string(4),
            'ifnsul' => $this->string(4),
            'terrifnsul' => $this->string(4),
            'okato' => $this->string(11),
            'oktmo' => $this->string(11),
            'updatedate' => $this->dateTime(),
            'cadnum' => $this->string(100),
            'housenum' => $this->string(20),
            'eststatus' => $this->integer(),
            'buildnum' => $this->string(10),
            'strucnum' => $this->string(10),
            'strstatus' => $this->integer(),
            'houseid' => 'uuid',
            'houseguid' => 'uuid',
            'aoguid' => 'uuid',
            'startdate' => $this->dateTime(),
            'enddate' => $this->dateTime(),
            'statstatus' => $this->integer(),
            'normdoc' => 'uuid',
            'counter' => $this->integer(),
            'divtype' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('fias_house');
        $this->dropTable('fias_addrobj');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200107124248RecreateFiasTables cannot be reverted.\n";

        return false;
    }
    */
}
