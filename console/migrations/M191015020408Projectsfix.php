<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191015020408Projectsfix
 */
class M191015020408Projectsfix extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('db_project', 'name');
        $this->addColumn('db_project', 'name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191015020408Projectsfix cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191015020408Projectsfix cannot be reverted.\n";

        return false;
    }
    */
}
