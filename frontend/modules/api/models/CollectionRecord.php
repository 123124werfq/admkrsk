<?php

namespace frontend\modules\api\models;

use common\models\CollectionColumn;
use Yii;
use yii\helpers\ArrayHelper;

class CollectionRecord extends \common\models\CollectionRecord
{
    private $_record;

    public function __get($name)
    {
        if (isset($this->_record[$name])) {
            return $this->_record[$name];
        }

        return parent::__get($name);
    }

    public function fields()
    {
        $record = $this->getRecord();

        $column_ids = [];
        foreach (array_keys($record) as $column) {
            $column_ids[$column] = str_replace('col', '', $column);
        }

        $this->_record = ArrayHelper::map(CollectionColumn::findAll(['id_column' => $column_ids]), 'alias', function($item) use ($record) {
            return $record['col' . $item->id_column];
        });

        return ArrayHelper::merge(['id_record'], array_keys($this->_record));
    }
}
