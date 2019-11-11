<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190916172100Pageurl
 */
class M190916172100Pageurl extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'path', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cnt_page', 'path');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190916172100Pageurl cannot be reverted.\n";

        return false;
    }
    */
}
