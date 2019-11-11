<?php

namespace console\migrations;

use common\models\User;
use Yii;
use yii\db\Migration;
use yii\helpers\ArrayHelper;

/**
 * Class M190930034342AlterRoleColumnInUserTable
 */
class M190930034342DropRoleColumnInUserTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%user}}', 'role');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%user}}', 'role', $this->string(32));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190930034342AlterRoleColumnInUserTable cannot be reverted.\n";

        return false;
    }
    */
}
