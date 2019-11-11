<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190916204400CorrectPKcollection
 */
class M190916204400CorrectPKcollection extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('db_collection_value');

        $this->createTable('db_collection_value', [
            'id_column' => $this->integer(),
            'id_record' => $this->integer(),
            'value' => $this->text(),
        ]);

        $this->addPrimaryKey('db_collection_value_pk', 'db_collection_value', ['id_column', 'id_record']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M190916204400CorrectPKcollection cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190916204400CorrectPKcollection cannot be reverted.\n";

        return false;
    }
    */
}
