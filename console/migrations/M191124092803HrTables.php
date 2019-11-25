<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191124092803HrTables
 */
class M191124092803HrTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('hr_profile', [
            'id_profile' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'id_record' => $this->integer(),
            'state' => $this->text(),
            'reserve_date' => $this->integer(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('hr_contest', [
            'id_contest' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'title' => $this->text(),
            'begin' => $this->integer(),
            'end' => $this->integer(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable( 'hr_expert', [
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

        $this->createTable('hrl_contest_profile', [
            'id_profile' => $this->integer(),
            'id_contest' => $this->integer(),
        ]);

        $this->createTable('hrl_profile_position', [
            'id_profile' => $this->integer(),
            'id_record' => $this->integer(),
            'position_title' => $this->text()
        ]);

        $this->createTable('hrl_contest_position', [
            'id_contest' => $this->integer(),
            'id_expert' => $this->integer(),
            'position_title' => $this->text()
        ]);

        $this->createTable('hrl_contest_expert', [
            'id_contest' => $this->integer(),
            'id_record' => $this->integer(),
            'message_sent' => $this->smallInteger()->defaultValue(0)
        ]);

        $this->createTable('hr_vote', [
            'id_vote' => $this->primaryKey(),
            'id_expert' => $this->integer(),
            'id_profile' => $this->integer(),
            'id_record' => $this->integer(),
            'value' => $this->integer()->defaultValue(0),
            'comment' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('hr_contest_result', [
            'id_result' => $this->primaryKey(),
            'id_contest' => $this->integer(),
            'id_profile' => $this->integer(),
            'id_record' => $this->integer(),
            'result' => $this->integer(),
            'comment' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('hr_contest_comment', [
            'id_comment' => $this->integer(),
            'id_expert' => $this->integer(),
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
        echo "M191124092803HrTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124092803HrTables cannot be reverted.\n";

        return false;
    }
    */
}
