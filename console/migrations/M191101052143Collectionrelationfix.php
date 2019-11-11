<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
/**
 * Class M191101052143Collectionrelationfix
 */
class M191101052143Collectionrelationfix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("DELETE FROM db_collection_value WHERE id_record NOT IN (SELECT id_record FROM db_collection_record)")->execute();
        Yii::$app->db->createCommand("DELETE FROM db_collection_value WHERE id_column NOT IN (SELECT id_column FROM db_collection_column)")->execute();

        //$this->addPrimaryKey('db_collection_value_pk', 'db_collection_value', ['id_column', 'id_record']);

        $this->addForeignKey('fk-db_collection_record-id_record-db_collection_value-id_record', 'db_collection_value', 'id_record', 'db_collection_record', 'id_record',  'CASCADE');
        $this->addForeignKey('fk-db_collection_column-id_column-db_collection_value-id_column', 'db_collection_value', 'id_column', 'db_collection_column', 'id_column', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191101052143Collectionrelationfix cannot be reverted.\n";

        return false;
    }
    */
}
