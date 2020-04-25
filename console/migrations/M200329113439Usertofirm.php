<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200329113439Usertofirm
 */
class M200329113439Usertofirm extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dbl_firm_user', [
            'id_record' => $this->integer()->notNull(),
            'id_user' => $this->integer()->notNull(),
            'state' => $this->integer()->defaultValue(0),
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
        echo "M200329113439Usertofirm cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200329113439Usertofirm cannot be reverted.\n";

        return false;
    }
    */
}
