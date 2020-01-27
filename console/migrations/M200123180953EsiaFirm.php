<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200123180953EsiaFirm
 */
class M200123180953EsiaFirm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('auth_esia_firm', [
            'id_esia_firm' => $this->primaryKey(),
            'id_user' => $this->integer(),

            'active' => $this->integer()->defaultValue(0),
            'oid' => $this->string(),
            'shortname' => $this->string(),
            'fullname' => $this->string(),
            'type' => $this->string(),
            'ogrn' => $this->string(),
            'inn' => $this->string(),
            'leg' => $this->string(),
            'kpp' => $this->string(),
            'ctts' => $this->string(),
            'email' => $this->string(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth_esia_firm');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200123180953EsiaFirm cannot be reverted.\n";

        return false;
    }
    */
}
