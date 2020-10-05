<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M201005200104NumberCommonAsText
 */
class M201005200104NumberCommonAsText extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%service_appeal}}', 'number_common', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M201005200104NumberCommonAsText cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M201005200104NumberCommonAsText cannot be reverted.\n";

        return false;
    }
    */
}
