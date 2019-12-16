<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191216095149AddApplicationTable
 */
class M191216095149AddApplicationTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_application', [
            'id_application' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'access_token' => $this->string(32)->notNull()->unique(),
            'is_active' => $this->boolean(),
            'created_at' => $this->integer(),
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
        $this->dropTable('db_application');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191216095149AddApplicationTable cannot be reverted.\n";

        return false;
    }
    */
}
