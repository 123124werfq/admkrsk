<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201031084454Idpagelink
 */
class M201031084454Idpagelink extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'id_page_link', $this->integer());
        $this->alterColumn('cnt_page', 'alias', 'DROP NOT NULL');
        $this->alterColumn('cnt_page', 'alias', 'SET DEFAULT NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201031084454Idpagelink cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201031084454Idpagelink cannot be reverted.\n";

        return false;
    }
    */
}
