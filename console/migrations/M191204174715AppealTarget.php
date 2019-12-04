<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191204174715AppealTarget
 */
class M191204174715AppealTarget extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('service_appeal', 'id_target', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191204174715AppealTarget cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191204174715AppealTarget cannot be reverted.\n";

        return false;
    }
    */
}
