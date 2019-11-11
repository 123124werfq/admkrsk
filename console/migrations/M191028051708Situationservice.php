<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191028051708Situationservice
 */
class M191028051708Situationservice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('servicel_situation', [
            'id_situation' => $this->integer(),
            'id_service'=>$this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('servicel_situation');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191028051708Situationservice cannot be reverted.\n";

        return false;
    }
    */
}
