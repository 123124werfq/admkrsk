<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;

/**
 * Class M191119151711Formretype
 */
class M191119151711Formretype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_input', 'id_column', $this->integer());
        $this->addColumn('form_input', 'type', $this->integer());

        Yii::$app->db->createCommand("UPDATE form_input fi SET type = (SELECT type FROM form_input_type WHERE id_type = fi.id_type)")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191119151711Formretype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191119151711Formretype cannot be reverted.\n";

        return false;
    }
    */
}
