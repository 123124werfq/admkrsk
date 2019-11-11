<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191111162945Inputlabel
 */
class M191111162945Inputlabel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'label', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191111162945Inputlabel cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191111162945Inputlabel cannot be reverted.\n";

        return false;
    }
    */
}
