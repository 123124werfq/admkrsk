<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M230528062602FormAdditionalParams
 */
class M230528062602FormAdditionalParams extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'button_caption', $this->text());
        $this->addColumn('form_form', 'timer_duration', $this->smallInteger()->defaultValue(0));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M230528062602FormAdditionalParams cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M230528062602FormAdditionalParams cannot be reverted.\n";

        return false;
    }
    */
}
