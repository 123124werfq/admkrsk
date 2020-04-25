<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200425095740Pkfirmuser
 */
class M200425095740Pkfirmuser extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addPrimaryKey('id_firm_id_user_pk','dbl_firm_user', ['id_record', 'id_user']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200425095740Pkfirmuser cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200425095740Pkfirmuser cannot be reverted.\n";

        return false;
    }
    */
}
