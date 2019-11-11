<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191103122733CreateInstitutionTables
 */
class M191103122733CreateInstitutionTables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_institution', [
            'id_institution' => $this->primaryKey(),
            'status' => $this->integer(),
            'description' => $this->text(),
            'comment' => $this->string(),
            'bus_id' => 'uuid',
            'is_updating' => $this->boolean(),
            'last_update' => $this->integer(),
            'fullname' => $this->string(),
            'shortname' => $this->string(),
            'type' => $this->string(),
            'founder' => $this->json(),
            'okved' => $this->json(),
            'okved_code' => $this->string(),
            'okved_name' => $this->string(),
            'ppo' => $this->string(),
            'ppo_oktmo_name' => $this->string(),
            'ppo_oktmo_code' => $this->string(),
            'ppo_okato_name' => $this->string(),
            'ppo_okato_code' => $this->string(),
            'okpo' => $this->string(),
            'okopf_name' => $this->string(),
            'okopf_code' => $this->string(),
            'okfs_name' => $this->string(),
            'okfs_code' => $this->string(),
            'oktmo_name' => $this->string(),
            'oktmo_code' => $this->string(),
            'okato_name' => $this->string(),
            'okato_code' => $this->string(),
            'address_zip' => $this->string(),
            'address_subject' => $this->string(),
            'address_region' => $this->string(),
            'address_locality' => $this->string(),
            'address_street' => $this->string(),
            'address_building' => $this->string(),
            'address_latitude' => $this->string(),
            'address_longitude' => $this->string(),
            'vgu_name' => $this->string(),
            'vgu_code' => $this->string(),
            'inn' => $this->string(),
            'kpp' => $this->string(),
            'ogrn' => $this->string(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'website' => $this->string(),
            'manager_position' => $this->string(),
            'manager_firstname' => $this->string(),
            'manager_middlename' => $this->string(),
            'manager_lastname' => $this->string(),
            'version' => $this->string(),
            'modified_at' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->createTable('db_institution_document', [
            'id_institution_document' => $this->primaryKey(),
            'id_institution' => $this->integer()->notNull(),
            'type' => $this->string(),
            'name' => $this->string(),
            'url' => $this->string(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ]);

        $this->addForeignKey('fk-db_institution_document-id_institution-db_institution-id_institution', 'db_institution_document', 'id_institution', 'db_institution', 'id_institution', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-db_institution_document-id_institution-db_institution-id_institution', 'db_institution_document');

        $this->dropTable('db_institution');
        $this->dropTable('db_institution_document');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191103122733CreateInstitutionTables cannot be reverted.\n";

        return false;
    }
    */
}
