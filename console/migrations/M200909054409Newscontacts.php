<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\News;
/**
 * Class M200909054409Newscontacts
 */
class M200909054409Newscontacts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('db_news', 'contacts', $this->integer().'[]');

        $news = News::find()->where('id_record_contact IS NOT NULL')->all();

        foreach ($news as $key => $data) {
            $data->contacts = [$data->id_record_contact];
            $data->update();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200909054409Newscontacts cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200909054409Newscontacts cannot be reverted.\n";

        return false;
    }
    */
}
