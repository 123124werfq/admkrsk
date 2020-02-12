<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200212220510Collectintypecolumn
 */
class M200212220510Collectintypecolumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('collection_type_column', [
            'id_column' => $this->primaryKey(),
            'id_type' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'alias' => $this->string()->notNull(),
            'type'=> $this->integer()->notNull(),
            'settings'=> $this->text(),
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
        echo "M200212220510Collectintypecolumn cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200212220510Collectintypecolumn cannot be reverted.\n";

        return false;
    }
    */
}
