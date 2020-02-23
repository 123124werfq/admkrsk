<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200223122809ContestTables
 */
class M200223122809ContestTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('cst_profile', [
            'id_profile' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'id_record_anketa' => $this->integer(),
            'id_record_contest' => $this->integer(),
            'state' => $this->smallInteger()->defaultValue(0),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable( 'cst_expert', [
            'id_expert' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'state' => $this->smallInteger()->defaultValue(1),
            'comment' => $this->text(),
            'id_media' => $this->integer(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('cst_contest_expert', [
            'id_record_contest' => $this->integer(),
            'id_expert' => $this->integer(),
            'message_sent' => $this->smallInteger()->defaultValue(0)
        ]);

        $this->createTable('cst_vote', [
            'id_vote' => $this->primaryKey(),
            'id_expert' => $this->integer(),
            'id_profile' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(0),
            'value' => $this->integer()->defaultValue(0),
            'comment' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('cst_contest_result', [
            'id_result' => $this->primaryKey(),
            'id_record_contest' => $this->integer(),
            'id_profile' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(0),
            'result' => $this->integer(),
            'comment' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200223122809ContestTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200223122809ContestTables cannot be reverted.\n";

        return false;
    }
    */
}
