<?php

namespace console\migrations;

use common\models\Faq;
use common\models\FaqCategory;
use common\models\FaqFaqCategory;
use Yii;
use yii\db\Migration;

/**
 * Class M200209154353AlterFaqCategoryTable
 */
class M200209154353AlterFaqCategoryTable extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        Yii::$app->db->createCommand('TRUNCATE TABLE ' . Yii::$app->db->quoteTableName('{{%db_faq}}') . ' CASCADE')->execute();
        Yii::$app->db->createCommand('TRUNCATE TABLE ' . Yii::$app->db->quoteTableName('{{%db_faq_category}}') . ' CASCADE')->execute();

        $this->addColumn('{{%db_faq_category}}', 'lft', $this->integer()->notNull());
        $this->addColumn('{{%db_faq_category}}', 'rgt', $this->integer()->notNull());
        $this->addColumn('{{%db_faq_category}}', 'depth', $this->integer()->notNull());

        $root = new FaqCategory(['title' => 'Вопросы и ответы']);
        $root->makeRoot();

        $this->createIndex('idx-db_faq_category-lft', '{{%db_faq_category}}', ['lft', 'rgt']);
        $this->createIndex('idx-db_faq_category-rgt', '{{%db_faq_category}}', ['rgt']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%db_faq_category}}', 'lft');
        $this->dropColumn('{{%db_faq_category}}', 'rgt');
        $this->dropColumn('{{%db_faq_category}}', 'depth');

        FaqCategory::deleteAll(['title' => 'Вопросы и ответы']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200209154353AlterFaqCategoryTable cannot be reverted.\n";

        return false;
    }
    */
}
