<?php

namespace console\migrations;

use \yii\db\Migration;

/**
 * Class m190124_110200_add_verification_token_column_to_user_table
 */
class m190124_110200_add_verification_token_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'verification_token', $this->string()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%user}}', 'verification_token');
    }
}
