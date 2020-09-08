<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200908053215Dashboard
 */
class M200908053215Dashboard extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%db_dashboard}}', [
            'id_dashboard' => $this->primaryKey(),
            'id_user' => $this->integer(),
            'id_usergroup' => $this->integer(),
            'name' => $this->string(),
            'link' => $this->string(),
            'icon' => $this->string(),
            'ord' => $this->integer(),
            'update_at' => $this->integer(),
            'created_by' => $this->integer(),
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
        echo "M200908053215Dashboard cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200908053215Dashboard cannot be reverted.\n";

        return false;
    }
    */
}
