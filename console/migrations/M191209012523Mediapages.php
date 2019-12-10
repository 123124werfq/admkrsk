<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191209012523Mediapages
 */
class M191209012523Mediapages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_media', 'pagecount', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191209012523Mediapages cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191209012523Mediapages cannot be reverted.\n";

        return false;
    }
    */
}
