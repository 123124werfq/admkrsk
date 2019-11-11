<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191013191953Projectsevents
 */
class M191013191953Projectsevents extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_project', [
            'id_project' => $this->primaryKey(),
            'id_media' => $this->integer(),
            'id_page' => $this->integer(),
            'name' => $this->integer(),
            'type' => $this->integer(),
            'date_begin' => $this->integer(),
            'date_end' => $this->integer(),
            'url' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_project');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191013191953Projectsevents cannot be reverted.\n";

        return false;
    }
    */
}
