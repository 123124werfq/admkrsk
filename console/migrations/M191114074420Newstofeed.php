<?php

namespace console\migrations;

use yii\db\Migration;

/**
 * Class M191114074420Newstofeed
 */
class M191114074420Newstofeed extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('form_input', 'visibleInputValue', $this->text().'[]');
    }

    public function beforeValidate()
    {
        if (!empty($this->date_begin) && !is_numeric($this->date_begin))
            $this->date_begin = strtotime($this->date_begin);

        if (!empty($this->date_end) && !is_numeric($this->date_end))
            $this->date_end = strtotime($this->date_end);

        return parent::beforeValidate();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M191114074420Newstofeed cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M191114074420Newstofeed cannot be reverted.\n";

        return false;
    }
    */
}
