<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190928062703SedWorkflow
 */
class M190928062703SedWorkflow extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_pdocument', [
            'id_pdocument' => $this->primaryKey(),
            'id_message'=> $this->string(255),
            'sender_code'=> $this->string(255),
            'sender_name'=> $this->string(255),
            'recipient_code'=> $this->string(255),
            'recipient_name'=> $this->string(255),
            'originator_code'=> $this->string(255),
            'originator_name'=> $this->string(255),
            'case_number'=> $this->string(255),
            'service_code'=> $this->string(255),
            'type' => $this->string(255),
            'regnum' => $this->string(255),
            'regdep' => $this->string(255),
            'regdate' => $this->string(255),
            'subject' => $this->text(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_pdocument_link', [
            'id_pdocument_link' => $this->primaryKey(),
            'id_pdocument' => $this->integer(),
            'id_message'=> $this->string(255)->notNull(),
            'type' => $this->string(255),
            'regnum' => $this->string(255),
            'regdate' => $this->string(255),
            'subject' => $this->text(),
            'linkname' => $this->string(255),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

        $this->createTable('db_pdocument_file', [
            'id_pdocument_file' => $this->primaryKey(),
            'id_pdocument' => $this->integer(),
            'id_message'=> $this->string(255)->notNull(),
            'id_media'=> $this->integer(),
            'name' => $this->string(255),
            'description' => $this->text(),
            'requestcode' => $this->string(255),
            'digest' => $this->string(255),

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
        echo "M190928062703SedWorkflow cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190928062703SedWorkflow cannot be reverted.\n";

        return false;
    }
    */
}
