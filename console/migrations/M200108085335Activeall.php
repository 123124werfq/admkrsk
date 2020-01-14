<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
/**
 * Class M200108085335Activeall
 */
class M200108085335Activeall extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand()->update('cnt_page',['active'=>1])->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200108085335Activeall cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200108085335Activeall cannot be reverted.\n";

        return false;
    }
    */
}
