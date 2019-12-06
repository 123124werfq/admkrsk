<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191203194500Inputnamealiaslantght
 */
class M191203194500Inputnamealiaslantght extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('form_input', 'name', $this->string(500)); 
        $this->alterColumn('form_input', 'fieldname', $this->string(500)); 
        $this->alterColumn('db_collection_column', 'alias', $this->string(500)); 
        $this->alterColumn('db_collection_column', 'name', $this->string(500)); 
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191203194500Inputnamealiaslantght cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191203194500Inputnamealiaslantght cannot be reverted.\n";

        return false;
    }
    */
}
