<?php

namespace console\migrations;

use yii\db\Migration;
use common\models\FormInput;
use common\models\CollectionColumn;
/**
 * Class M200727100150Newvalues
 */
class M200727100150Newvalues extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $inputs = FormInput::find()->where(['type'=>[CollectionColumn::TYPE_SELECT,CollectionColumn::TYPE_RADIO,CollectionColumn::TYPE_CHECKBOXLIST,CollectionColumn::TYPE_CHECKBOX]])->all();


        foreach ($inputs as $key => $data)
        {
            $values = explode(';', $data->values);

            foreach ($values as $key => $value) {
                $values[$key] = trim($value);
            }

            $data->values = json_encode($values);

            $data->updateAttributes(['values']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200727100150Newvalues cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M200727100150Newvalues cannot be reverted.\n";

        return false;
    }
    */
}
