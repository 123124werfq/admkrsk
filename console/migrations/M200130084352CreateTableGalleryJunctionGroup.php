<?php

namespace console\migrations;

use yii\db\Migration;

class M200130084352CreateTableGalleryJunctionGroup extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('galleries_groups_junction',[
            'id' => $this->primaryKey(),
            'gallery_group_id' => $this->integer(),
            'gallery_id' => $this->integer(),
            'created_at' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('galleries_groups_junction');
    }
}
