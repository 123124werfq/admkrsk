<?php

namespace console\migrations;

use Yii;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\pgsql\QueryBuilder;

/**
 * Class M191028141149AlterFiasTables
 */
class M191028141149AlterFiasTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addPrimaryKey('fias_addrobj_pkey', 'fias_addrobj', 'aoguid');

        $this->addForeignKey('fk-fias_addrobj-parentguid-fias_addrobj-aoguid', 'fias_addrobj', 'parentguid', 'fias_addrobj', 'aoguid', 'NO ACTION');

        $this->createIndex('idx-fias_addrobj-parentguid', 'fias_addrobj', 'parentguid');
        $this->createIndex('idx-fias_addrobj-aolevel', 'fias_addrobj', 'aolevel');
        $this->createIndex('idx-fias_addrobj-formalname', 'fias_addrobj', 'formalname');
        Yii::$app->db->createCommand('CREATE INDEX "idx-fias_addrobj-formalname_trgm" on fias_addrobj USING gin (formalname gin_trgm_ops);')->execute();

        $this->addPrimaryKey('fias_house_pkey', 'fias_house', 'houseguid');

        $this->addForeignKey('fk-fias_house-aoguid-fias_addrobj-aoguid', 'fias_house', 'aoguid', 'fias_addrobj', 'aoguid', 'NO ACTION');

        $this->createIndex('idx-fias_house-housenum', 'fias_house', 'housenum');
        $this->createIndex('idx-fias_house-aoguid', 'fias_house', 'aoguid');
        Yii::$app->db->createCommand('CREATE INDEX "idx-fias_house-housenum_trgm" on fias_house USING gin (housenum gin_trgm_ops);')->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-fias_addrobj-parentguid', 'fias_addrobj');
        $this->dropIndex('idx-fias_addrobj-aolevel', 'fias_addrobj');
        $this->dropIndex('idx-fias_addrobj-formalname', 'fias_addrobj');
        $this->dropIndex('idx-fias_addrobj-formalname_trgm', 'fias_addrobj');
        $this->dropIndex('idx-fias_house-housenum', 'fias_house');
        $this->dropIndex('idx-fias_house-aoguid', 'fias_house');
        $this->dropIndex('idx-fias_house-housenum_trgm', 'fias_house');

        $this->dropForeignKey('fk-fias_addrobj-parentguid-fias_addrobj-aoguid', 'fias_addrobj');
        $this->dropForeignKey('fk-fias_house-aoguid-fias_addrobj-aoguid', 'fias_house');

        $this->dropPrimaryKey('fias_addrobj_pkey', 'fias_addrobj');
        $this->dropPrimaryKey('fias_house_pkey', 'fias_house');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191028141149AlterFiasTables cannot be reverted.\n";

        return false;
    }
    */
}
