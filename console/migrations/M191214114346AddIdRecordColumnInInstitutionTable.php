<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191214114346AddIdRecordColumnInInstitutionTable
 */
class M191214114346AddIdRecordColumnInInstitutionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_institution', 'id_record', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('db_institution', 'id_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191214114346AddIdRecordColumnInInstitutionTable cannot be reverted.\n";

        return false;
    }
    */
}
