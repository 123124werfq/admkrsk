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
        if (in_array('fias_addrobj', Yii::$app->db->schema->tableNames)) {
            $this->dropTable('fias_addrobj');
        }

        if (in_array('fias_house', Yii::$app->db->schema->tableNames)) {
            $this->dropTable('fias_house');
        }

        $this->createTable('fias_addrobj', [
            'id' => $this->primaryKey(),
            'aoguid' => $this->string(36),
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
            'divtype' => $this->string(1),
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
            'parentguid' => $this->string(36),
            'aoid' => $this->string(36),
            'previd' => $this->string(36),
            'nextid' => $this->string(36),
            'code' => $this->string(17),
            'plaincode' => $this->string(15),
            'actstatus' => $this->integer(),
            'centstatus' => $this->integer(),
            'operstatus' => $this->integer(),
            'currstatus' => $this->integer(),
            'livestatus' => $this->integer(),
            'startdate' => $this->dateTime(),
            'enddate' => $this->dateTime(),
            'normdoc' => $this->string(36),
        ]);

        $this->createTable('fias_house', [
            'postalcode' => $this->string(6),
            'ifnsfl' => $this->string(4),
            'terrifnsfl' => $this->string(4),
            'ifnsul' => $this->string(4),
            'terrifnsul' => $this->string(4),
            'okato' => $this->string(11),
            'oktmo' => $this->string(11),
            'updatedate' => $this->dateTime(),
            'housenum' => $this->string(20),
            'eststatus' => $this->integer(),
            'buildnum' => $this->string(10),
            'strucnum' => $this->string(10),
            'strstatus' => $this->integer(),
            'houseid' => $this->string(36),
            'houseguid' => $this->string(36),
            'aoguid' => $this->string(36),
            'startdate' => $this->dateTime(),
            'enddate' => $this->dateTime(),
            'statstatus' => $this->integer(),
            'normdoc' => $this->string(36),
            'counter' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('fias_addrobj');
        $this->dropTable('fias_house');
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
