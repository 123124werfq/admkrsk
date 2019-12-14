<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191214112735HrTables
 */
class M191214112735HrTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // таблица связи анкеты с должностями reserve_positions
        $this->createTable('hr_profile_positions', [
            'id_profile_position' => $this->primaryKey(),
            'id_profile' => $this->integer(),
            'id_record_position' => $this->integer(),
            'id_result' => $this->integer(),
            'state' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        // таблица резерва
        $this->createTable('hr_reserve', [
            'id_reserve' => $this->primaryKey(),
            'id_profile' => $this->integer(),
            'id_record_position' => $this->integer(), //
            'id_result' => $this->integer(),
            'contest_date' => $this->integer(),
            'state' => $this->integer(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191214112735HrTables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191214112735HrTables cannot be reverted.\n";

        return false;
    }
    */
}
