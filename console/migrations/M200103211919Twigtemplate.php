<?php

namespace console\migrations;

use yii\db\Migration;
use  Yii;
/**
 * Class M200103211919Twigtemplate
 */
class M200103211919Twigtemplate extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand("UPDATE db_collection SET template = REPLACE(REPLACE(template,'{','{{'),'}','}}')")->execute();
        Yii::$app->db->createCommand("UPDATE db_collection SET template_element = REPLACE(REPLACE(template_element,'{','{{'),'}','}}')")->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200103211919Twigtemplate cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200103211919Twigtemplate cannot be reverted.\n";

        return false;
    }
    */
}
