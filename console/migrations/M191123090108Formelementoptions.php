<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191123090108Formelementoptions
 */
class M191123090108Formelementoptions extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('form_element', 'options', $this->json());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191123090108Formelementoptions cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191123090108Formelementoptions cannot be reverted.\n";

        return false;
    }
    */
}
