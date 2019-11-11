<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M190930200308Blockmedias
 */
class M190930200308Blockmedias extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('dbl_block_var_media', [
            'id_var' => $this->integer(),
            'id_media' => $this->integer(),
            'ord' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('dbl_block_var_media');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190930200308Blockmedias cannot be reverted.\n";

        return false;
    }
    */
}
