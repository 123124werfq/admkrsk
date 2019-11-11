<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191006084238Pageviews
 */
class M191006084238Pageviews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'views', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('cnt_page', 'views');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191006084238Pageviews cannot be reverted.\n";

        return false;
    }
    */
}
