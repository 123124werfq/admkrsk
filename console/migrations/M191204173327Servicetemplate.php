<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191204173327Servicetemplate
 */
class M191204173327Servicetemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_service', 'id_media_template', $this->integer());
        $this->addColumn('service_target', 'id_media_template', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191204173327Servicetemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191204173327Servicetemplate cannot be reverted.\n";

        return false;
    }
    */
}
