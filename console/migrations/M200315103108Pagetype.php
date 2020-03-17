<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
/**
 * Class M200315103108Pagetype
 */
class M200315103108Pagetype extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('cnt_page', 'type', $this->integer());

        $sql = "UPDATE cnt_page SET type = 2 WHERE id_page IN (SELECT id_page FROM db_news GROUP BY id_page)";
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200315103108Pagetype cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200315103108Pagetype cannot be reverted.\n";

        return false;
    }
    */
}
