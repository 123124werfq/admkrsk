<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200210220133Formcaptcha
 */
class M200210220133Formcaptcha extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'captcha', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200210220133Formcaptcha cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200210220133Formcaptcha cannot be reverted.\n";

        return false;
    }
    */
}
