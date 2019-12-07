<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191207052501Formtemplate
 */
class M191207052501Formtemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_form', 'id_media_template', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191207052501Formtemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191207052501Formtemplate cannot be reverted.\n";

        return false;
    }
    */
}
