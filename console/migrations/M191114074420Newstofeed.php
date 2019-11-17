<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191114074420Newstofeed
 */
class M191114074420Newstofeed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dbl_news_page', [
            'id_page' => $this->integer(),
            'id_news' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'accepted_at' => $this->integer(),
            'accepted_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191114074420Newstofeed cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191114074420Newstofeed cannot be reverted.\n";

        return false;
    }
    */
}
