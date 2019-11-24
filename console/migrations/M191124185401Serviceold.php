<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
/**
 * Class M191124185401Serviceold
 */
class M191124185401Serviceold extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "UPDATE service_service SET old = 0";
        Yii::$app->db->createCommand($sql)->execute();
        
        $sql = "UPDATE service_service SET old = 1 WHERE id_service IN (12,13,28,62,80,82,91,95,96,97,115,149,151,152,153,154,155,156,164,165,176,178,182,186,187,188,196,197,198,199);";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191124185401Serviceold cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191124185401Serviceold cannot be reverted.\n";

        return false;
    }
    */
}
