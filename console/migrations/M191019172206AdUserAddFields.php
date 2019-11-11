<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191019172206AdUserAddFields
 */
class M191019172206AdUserAddFields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_department', [
            'id_department' => $this->primaryKey(),
            'id_parent' => $this->integer(),
            'id_boss' => $this->integer(),

            'fullname' => $this->string(),
            'shortname' => $this->string(),
            'address' => $this->string(),
            'email' => $this->string(),
            'phone' => $this->string(),
            'fax' => $this->string(),

            'ord' => $this->integer(),

            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer()
        ]);


        $this->addColumn('{{%user}}', 'id_connected_user', $this->string());

        $this->addColumn('auth_ad_user', 'public', $this->integer()->defaultValue(0));

        $this->addColumn('auth_ad_user', 'city', $this->string());
        $this->addColumn('auth_ad_user', 'company', $this->string());
        $this->addColumn('auth_ad_user', 'department', $this->string());
        $this->addColumn('auth_ad_user', 'id_department', $this->integer());
        $this->addColumn('auth_ad_user', 'description', $this->string());
        $this->addColumn('auth_ad_user', 'displayname', $this->string());
        $this->addColumn('auth_ad_user', 'email', $this->string());
        $this->addColumn('auth_ad_user', 'givenname', $this->string());
        $this->addColumn('auth_ad_user', 'fax', $this->string());
        $this->addColumn('auth_ad_user', 'otherphones', $this->string());
        $this->addColumn('auth_ad_user', 'phone', $this->string());
        $this->addColumn('auth_ad_user', 'office', $this->string());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191019172206AdUserAddFields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191019172206AdUserAddFields cannot be reverted.\n";

        return false;
    }
    */
}
