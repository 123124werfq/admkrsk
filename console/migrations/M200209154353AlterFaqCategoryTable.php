<?php

namespace console\migrations;

use common\models\FaqCategory;
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
        $this->addColumn('{{%db_faq_category}}', 'lft', $this->integer());
        $this->addColumn('{{%db_faq_category}}', 'rgt', $this->integer());
        $this->addColumn('{{%db_faq_category}}', 'depth', $this->integer());

        $categories = FaqCategory::find()->all();

        $root = new FaqCategory(['title' => 'Вопросы и ответы']);
        $root->makeRoot();

        foreach ($categories as $category) {
            $category->appendTo($root);
        }

        $this->createIndex('idx-db_faq_category-lft', '{{%db_faq_category}}', ['lft', 'rgt']);
        $this->createIndex('idx-db_faq_category-rgt', '{{%db_faq_category}}', ['rgt']);

        $this->alterColumn('{{%db_faq_category}}', 'lft', $this->integer()->notNull());
        $this->alterColumn('{{%db_faq_category}}', 'rgt', $this->integer()->notNull());
        $this->alterColumn('{{%db_faq_category}}', 'depth', $this->integer()->notNull());
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
