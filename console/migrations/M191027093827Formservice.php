<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191027093827Formservice
 */
class M191027093827Formservice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'id_form', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('service_service', 'id_form');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191027093827Formservice cannot be reverted.\n";

        return false;
    }
    */
}
