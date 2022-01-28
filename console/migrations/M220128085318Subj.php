<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M220128085318Subj
 */
class M220128085318Subj extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'subject', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M220128085318Subj cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M220128085318Subj cannot be reverted.\n";

        return false;
    }
    */
}
