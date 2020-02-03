<?php

namespace console\migrations;

use yii\db\Migration;

class M200130083501CreateTableGalleryGroup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('galleries_groups', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('galleries_groups');
    }
}
