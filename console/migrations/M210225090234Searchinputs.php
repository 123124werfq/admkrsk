<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M210225090234Searchinputs
 */
class M210225090234Searchinputs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'search_inputs', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M210225090234Searchinputs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M210225090234Searchinputs cannot be reverted.\n";

        return false;
    }
    */
}
