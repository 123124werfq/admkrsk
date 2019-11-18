<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191117163327Newsfreedpk
 */
class M191117163327Newsfreedpk extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'hide_menu', $this->integer()->defaultValue(0));
        $this->addColumn('service_target', 'target', $this->string());
        $this->addColumn('service_target', 'target_code', $this->string());
        $this->addColumn('service_target', 'service_code', $this->string());
        $this->addColumn('service_target', 'obj_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191117163327Newsfreedpk cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191117163327Newsfreedpk cannot be reverted.\n";

        return false;
    }
    */
}
