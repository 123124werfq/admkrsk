<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200321072612ContestAnketaComments
 */
class M200321072612ContestAnketaComments extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cst_profile', 'comment', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200321072612ContestAnketaComments cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200321072612ContestAnketaComments cannot be reverted.\n";

        return false;
    }
    */
}
