<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\Page;
/**
 * Class M191012081010Fixpath
 */
class M191012081010Fixpath extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $pages = Page::find()->orderBy('id_parent ASC')->all();

        foreach ($pages as $key => $data)
            $data->createPath();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191012081010Fixpath cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191012081010Fixpath cannot be reverted.\n";

        return false;
    }
    */
}
