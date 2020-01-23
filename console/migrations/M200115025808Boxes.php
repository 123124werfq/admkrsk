<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200115025808Boxes
 */
class M200115025808Boxes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('db_box', [
            'id_box' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addColumn('form_form', 'id_box', $this->integer());
        $this->addColumn('db_collection', 'id_box', $this->integer());
        $this->addColumn('db_gallery', 'id_box', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200115025808Boxes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200115025808Boxes cannot be reverted.\n";

        return false;
    }
    */
}
