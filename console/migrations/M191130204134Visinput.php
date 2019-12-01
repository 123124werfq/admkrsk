<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191130204134Visinput
 */
class M191130204134Visinput extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('forml_visibleinput');

        $this->createTable('form_visibleinput', [
            'id' => $this->primaryKey(),
            'id_input' => $this->integer(),
            'id_input_visible' => $this->integer(),
            'values' => $this->text().'[]',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191130204134Visinput cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191130204134Visinput cannot be reverted.\n";

        return false;
    }
    */
}
