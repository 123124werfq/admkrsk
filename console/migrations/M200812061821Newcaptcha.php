<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\FormInput;
use common\models\CollectionColumn;
/**
 * Class M200812061821Newcaptcha
 */
class M200812061821Newcaptcha extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('form_form', 'captcha');
        $this->addColumn('form_form', 'captcha', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200812061821Newcaptcha cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200812061821Newcaptcha cannot be reverted.\n";

        return false;
    }
    */
}
