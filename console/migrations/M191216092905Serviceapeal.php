<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191216092905Serviceapeal
 */
class M191216092905Serviceapeal extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_appeal_form', [
            'id_appeal' => $this->primaryKey(),
            'id_form' => $this->integer(),
            'id_record_firm' => $this->integer(),
            'id_record_category' => $this->integer(),
            'id_service' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191216092905Serviceapeal cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191216092905Serviceapeal cannot be reverted.\n";

        return false;
    }
    */
}
