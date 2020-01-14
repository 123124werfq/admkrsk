<?php

namespace console\migrations;

use yii\db\Migration;

class M200106092849AddColumnNotifyRuleInCollectionModel extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection', 'notify_rule', $this->integer());
        $this->addColumn('db_collection', 'notify_message', $this->string());

        $this->addColumn('cnt_page', 'notify_rule', $this->integer());
        $this->addColumn('cnt_page', 'notify_message', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('db_collection', 'notify_rule');
        $this->dropColumn('db_collection', 'notify_message');

        $this->dropColumn('cnt_page', 'notify_rule');
        $this->dropColumn('cnt_page', 'notify_message');
    }
}
