<?php

namespace console\migrations;

use yii\db\Migration;

class M200104165029Notification extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('notify_settings', [
            'class' => 'common\models\Page',
            'message' => 'За текущий сутки произошли изменения в разделе.',
            'main_notify' => 1,
            'repeat_notify' => 1,
            'created_at' => 1578135880,
            'updated_at' => 1578135880,
        ]);

        $this->insert('notify_settings', [
            'class' => 'common\models\Collection',
            'message' => 'За текущий сутки произошли изменения в списке',
            'main_notify' => 1,
            'repeat_notify' => 1,
            'created_at' => 1578135880,
            'updated_at' => 1578135880,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "M200104165029Notification cannot be reverted.\n";
        return false;
    }
}
