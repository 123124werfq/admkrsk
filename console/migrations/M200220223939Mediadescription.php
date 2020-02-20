<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200220223939Mediadescription
 */
class M200220223939Mediadescription extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_media', 'description', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200220223939Mediadescription cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200220223939Mediadescription cannot be reverted.\n";

        return false;
    }
    */
}
