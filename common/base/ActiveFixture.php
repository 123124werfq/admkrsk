<?php

namespace common\base;

use yii\test\ActiveFixture as BaseActiveFixtureAlias;

class ActiveFixture extends BaseActiveFixtureAlias
{
    /**
     * {@inheritdoc}
     */
    public $dataDirectory = '@common/fixtures/data';

    /**
     * {@inheritdoc}
     */
    public function load()
    {
        $this->data = [];
        $table = $this->getTableSchema();
        foreach ($this->getData() as $alias => $row) {
            $primaryKeys = $this->db->schema->insert($table->fullName, $row);
            $this->data[$alias] = array_merge($row, $primaryKeys);
        }
        if ($table->sequenceName !== null) {
            $this->db->createCommand()->executeResetSequence($table->fullName);
        }
    }
}
