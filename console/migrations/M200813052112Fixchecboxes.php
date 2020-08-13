<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\FormInput;
use common\models\CollectionColumn;

/**
 * Class M200813052112Fixchecboxes
 */
class M200813052112Fixchecboxes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $inputs = FormInput::find()->where(['type'=>CollectionColumn::TYPE_CHECKBOX])->all();

        foreach ($inputs as $key => $data)
        {
            $values = json_decode($data->values,true);

            if (is_array($values))
            {
                $data->values = implode(';', $values);

                var_dump($data->values);
                $data->updateAttributes(['values']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200813052112Fixchecboxes cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200813052112Fixchecboxes cannot be reverted.\n";

        return false;
    }
    */
}
