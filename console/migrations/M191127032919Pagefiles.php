<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191127032919Pagefiles
 */
class M191127032919Pagefiles extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191127032919Pagefiles cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191127032919Pagefiles cannot be reverted.\n";

        return false;
    }
    */
}
