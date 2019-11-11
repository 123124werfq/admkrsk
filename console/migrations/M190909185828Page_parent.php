<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190909185828Page_parent
 */
class M190909185828Page_parent extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'id_parent', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cnt_page', 'id_parent');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190909185828Page_parent cannot be reverted.\n";

        return false;
    }
    */
}
