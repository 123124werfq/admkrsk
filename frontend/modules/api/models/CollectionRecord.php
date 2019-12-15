<?php

namespace frontend\modules\api\models;

use common\models\CollectionColumn;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\mongodb\Query;

class CollectionRecord extends \common\models\CollectionRecord
{
    private $_record;

    public function __get($name)
    {
        if ($this->hasAttribute($name)) {
            return parent::__get($name);
        }

        $record = $this->getRecord();

        if (array_key_exists($name, $record)) {
            return $record[$name];
        }

        return parent::__get($name);
    }

    public function fields()
    {
        $record = $this->getRecord();

        return ArrayHelper::merge(['id_record'], array_keys($record));
    }

    public function getRecord()
    {
        if (!$this->_record) {
            $record = (new Query())
                ->from('collection' . $this->id_collection)
                ->where(['id_record' => $this->id_record])
                ->one();

            if (isset($record['_id'])) {
                unset($record['_id']);
            }

            if (isset($record['id_record'])) {
                unset($record['id_record']);
            }

            $column_ids = [];
            foreach (array_keys($record) as $column) {
                $column_ids[$column] = str_replace('col', '', $column);
            }

            $this->_record = ArrayHelper::map(CollectionColumn::findAll(['id_column' => $column_ids]), 'alias', function(CollectionColumn $item) use ($record) {
                $value = $record['col' . $item->id_column];
                if ($item->type == CollectionColumn::TYPE_JSON) {
                    $value = Json::decode($value);
                }
                return $value;
            });
        }

        return $this->_record;
    }
}
