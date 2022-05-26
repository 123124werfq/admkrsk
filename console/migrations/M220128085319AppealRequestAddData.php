<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M220128085319AppealRequestAddData
 */
class M220128085319AppealRequestAddData extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('appeal_request', 'data', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M220128085319AppealRequestAddData cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M220128085319AppealRequestAddData cannot be reverted.\n";

        return false;
    }
    */
}
