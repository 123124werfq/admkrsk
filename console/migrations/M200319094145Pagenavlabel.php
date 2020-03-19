<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200319094145Pagenavlabel
 */
class M200319094145Pagenavlabel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'label', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200319094145Pagenavlabel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200319094145Pagenavlabel cannot be reverted.\n";

        return false;
    }
    */
}
