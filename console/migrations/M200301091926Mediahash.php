<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200301091926Mediahash
 */
class M200301091926Mediahash extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_media', 'hash', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200301091926Mediahash cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200301091926Mediahash cannot be reverted.\n";

        return false;
    }
    */
}
