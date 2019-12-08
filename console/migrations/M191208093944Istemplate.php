<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191208093944Istemplate
 */
class M191208093944Istemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'is_template', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191208093944Istemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191208093944Istemplate cannot be reverted.\n";

        return false;
    }
    */
}
