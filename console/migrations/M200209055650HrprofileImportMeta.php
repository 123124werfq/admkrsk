<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M200209055650HrprofileImportMeta
 */
class M200209055650HrprofileImportMeta extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('hr_profile', 'import_author', $this->string());
        $this->addColumn('hr_profile', 'import_candidateid', $this->string());
        $this->addColumn('hr_profile', 'import_timestamp', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('hr_profile', 'import_author');
        $this->dropColumn('hr_profile', 'import_candidateid');
        $this->dropColumn('hr_profile', 'import_timestamp');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200209055650HrprofileImportMeta cannot be reverted.\n";

        return false;
    }
    */
}
