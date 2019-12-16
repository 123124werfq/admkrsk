<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191216111706HrVotes
 */
class M191216111706HrVotes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('hr_vote', 'id_contest', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191216111706HrVotes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191216111706HrVotes cannot be reverted.\n";

        return false;
    }
    */
}
