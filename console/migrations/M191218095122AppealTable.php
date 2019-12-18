<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191218095122AppealTable
 */
class M191218095122AppealTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('auth_esia_user', 'passport_serie', $this->text());
        $this->addColumn('auth_esia_user', 'passport_number', $this->text());
        $this->addColumn('auth_esia_user', 'passport_date', $this->text());
        $this->addColumn('auth_esia_user', 'passport_issuer', $this->text());
        $this->addColumn('auth_esia_user', 'passport_issuer_id', $this->text());
        $this->addColumn('auth_esia_user', 'passport_comments', $this->text());


        $this->addColumn('auth_esia_user', 'userdoc_raw', $this->text());
        $this->addColumn('auth_esia_user', 'mediacal_raw', $this->text());
        $this->addColumn('auth_esia_user', 'residence_raw', $this->text());


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191218095122AppealTable cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191218095122AppealTable cannot be reverted.\n";

        return false;
    }
    */
}
