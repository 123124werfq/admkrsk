<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200309070555Message404
 */
class M200309070555Message404 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'hidden_message', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200309070555Message404 cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200309070555Message404 cannot be reverted.\n";

        return false;
    }
    */
}
