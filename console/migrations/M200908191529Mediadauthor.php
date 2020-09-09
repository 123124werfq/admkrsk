<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200908191529Mediadauthor
 */
class M200908191529Mediadauthor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_media', 'author', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200908191529Mediadauthor cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200908191529Mediadauthor cannot be reverted.\n";

        return false;
    }
    */
}
