<?php

namespace console\migrations;

use common\models\Vars;
use yii\db\Migration;

/**
 * Class M191215074130AddOpendataVersionInVarsTable
 */
class M191215074130AddOpendataVersionInVarsTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $var = new Vars([
            'name' => 'Версия методических рекомендаций (Открытые данные)',
            'alias' => 'opendata_version',
            'content' => 'http://data.gov.ru/metodicheskie-rekomendacii-po-publikacii-otkrytyh-dannyh-versiya-30',
        ]);
        $var->save();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        Vars::deleteAll(['alias' => 'opendata_version']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191215074130AddOpendataVersionInVarsTable cannot be reverted.\n";

        return false;
    }
    */
}
