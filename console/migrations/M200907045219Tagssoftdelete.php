<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200907045219Tagssoftdelete
 */
class M200907045219Tagssoftdelete extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_tag', 'created_by', $this->integer());
        $this->addColumn('db_tag', 'created_at', $this->integer());
        $this->addColumn('db_tag', 'updated_at', $this->integer());
        $this->addColumn('db_tag', 'updated_by', $this->integer());
        $this->addColumn('db_tag', 'deleted_at', $this->integer());
        $this->addColumn('db_tag', 'deleted_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200907045219Tagssoftdelete cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200907045219Tagssoftdelete cannot be reverted.\n";

        return false;
    }
    */
}
