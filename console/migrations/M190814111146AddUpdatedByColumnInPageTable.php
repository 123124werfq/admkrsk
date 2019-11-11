<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190814111146AddUpdatedByColumnInPageTable
 */
class M190814111146AddUpdatedByColumnInPageTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cnt_page}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cnt_page}}', 'updated_by');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190814111146AddUpdatedByColumnInPageTable cannot be reverted.\n";

        return false;
    }
    */
}
