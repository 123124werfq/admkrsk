<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191025040150Municipalservice
 */
class M191025040150Municipalservice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('service_service', [
            'id_service' => $this->primaryKey(),
            'id_rub'=> $this->integer(),
            'reestr_number'=> $this->string(),
            'fullname'=> $this->text(),
            'name'=> $this->string(),
            'keywords'=> $this->text(),
            'addresses'=> $this->text(),
            'result'=> $this->text(),
            'client_type'=> $this->integer(),
            'client_category'=> $this->text(),
            'duration'=> $this->text(),
            'refuse'=> $this->text(),
            'documents'=> $this->text(),
            'price'=> $this->text(),
            'appeal'=> $this->text(),
            'appeal'=> $this->text(),
            'legal_grounds' => $this->text(),
            'regulations' => $this->text(),
            'regulations_link' => $this->text(),
            'duration_order' => $this->text(),
            'availability' => $this->text(),
            'procedure_information' => $this->text(),
            'procedure_information' => $this->text(),
            'max_duration_queue'=> $this->text(),
            'old'=> $this->integer(),
            'online'=> $this->integer(),
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
        $this->dropTable('service_service');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191025040150Municipalservice cannot be reverted.\n";

        return false;
    }
    */
}
