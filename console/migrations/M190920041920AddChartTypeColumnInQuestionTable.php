<?php

namespace console\migrations;

use common\models\Question;
use yii\db\Migration;

/**
 * Class M190920041920AddChartTypeColumnInQuestionTable
 */
class M190920041920AddChartTypeColumnInQuestionTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%db_poll_question}}', 'chart_type', $this->smallInteger()->defaultValue(Question::CHART_TYPE_BAR_V));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%db_poll_question}}', 'chart_type');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M190920041920AddChartTypeColumnInQuestionTable cannot be reverted.\n";

        return false;
    }
    */
}
