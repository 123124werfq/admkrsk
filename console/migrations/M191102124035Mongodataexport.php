<?php

namespace console\migrations;

use yii\db\Migration;
use Yii;
use common\models\CollectionRecord;
/**
 * Class M191102124035Mongodataexport
 */
class M191102124035Mongodataexport extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $results = Yii::$app->db->createCommand("SELECT * FROM db_collection_value ORDER BY id_record")->queryAll();

        $records = CollectionRecord::find()->indexBy('id_record')->all();
        $id_record = null;

        foreach ($results as $key => $data)
        {
            if (empty($id_record))
            {
                $insert = [];
                $id_record = $data['id_record'];
            }

            if ($id_record != $data['id_record'] || $key==(count($results)-1))
            {
                $collection = Yii::$app->mongodb->getCollection('collection'.$records[$id_record]->id_collection);
                $insert['id_record'] = $id_record;
                $collection->insert($insert);
                $insert = [];

                $id_record = $data['id_record'];
            }

            $insert[$data['id_column']] = $data['value'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191102124035Mongodataexport cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191102124035Mongodataexport cannot be reverted.\n";

        return false;
    }
    */
}
