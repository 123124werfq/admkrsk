<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191214120800HrUpdate
 */
class M191214120800HrUpdate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('hrl_contest_position');
        $this->dropTable('hrl_contest_expert');

        $this->createTable('hrl_contest_expert', [
            'id_contest' => $this->integer(),
            'id_expert' => $this->integer(),
            'message_sent' => $this->smallInteger()->defaultValue(0)
        ]);

        $this->addColumn('hr_profile_positions', 'name', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191214120800HrUpdate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191214120800HrUpdate cannot be reverted.\n";

        return false;
    }
    */
}
