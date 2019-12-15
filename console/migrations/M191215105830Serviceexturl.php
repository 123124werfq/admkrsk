<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191215105830Serviceexturl
 */
class M191215105830Serviceexturl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'ext_url', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191215105830Serviceexturl cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191215105830Serviceexturl cannot be reverted.\n";

        return false;
    }
    */
}
