<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191130115001Multiplevisible
 */
class M191130115001Multiplevisible extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('forml_visibleinput', [
            'id_input' => $this->integer(),
            'id_input_visible' => $this->integer(),
            'values' => $this->text().'[]',
        ]);

        $this->dropColumn('form_input', 'visibleInput');
        $this->dropColumn('form_input', 'visibleInputValue');

        $this->addPrimaryKey('forml_visibleinput_pk', 'forml_visibleinput', ['id_input', 'id_input_visible']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191130115001Multiplevisible cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191130115001Multiplevisible cannot be reverted.\n";

        return false;
    }
    */
}
