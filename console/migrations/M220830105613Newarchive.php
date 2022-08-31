<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\CollectionColumn;

/**
 * Class M220830105613Newarchive
 */
class M220830105613Newarchive extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_collection_record', 'is_archive', $this->smallInteger()->defaultValue(0));
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M220830105613Newarchive cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M220830105613Newarchive cannot be reverted.\n";

        return false;
    }
    */
}
