<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191213113117Hintlabelfix
 */
class M191213113117Hintlabelfix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('form_input', 'hint', $this->text());
        $this->alterColumn('form_input', 'label', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191213113117Hintlabelfix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191213113117Hintlabelfix cannot be reverted.\n";

        return false;
    }
    */
}
