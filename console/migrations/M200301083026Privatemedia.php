<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200301083026Privatemedia
 */
class M200301083026Privatemedia extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_media', 'is_private', $this->boolean());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200301083026Privatemedia cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200301083026Privatemedia cannot be reverted.\n";

        return false;
    }
    */
}
