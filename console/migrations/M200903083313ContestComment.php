<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200903083313ContestComment
 */
class M200903083313ContestComment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('cst_profile', 'comment', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200903083313ContestComment cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200903083313ContestComment cannot be reverted.\n";

        return false;
    }
    */
}
