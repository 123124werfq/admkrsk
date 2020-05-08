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

        return $record ? ArrayHelper::merge(['id_record'], array_keys($record)) : [];
    }

    public function getRecord()
    {
        if (!$this->_record) {
            $collection = \common\models\Collection::findOne($this->id_collection);

            $record = (new Query())
                ->from('collection' . $this->id_collection)
                ->where(['id_record' => $this->id_record])
                ->one();

            foreach ($collection->columns as $column) {
                if (isset($record['col' . $column->id_column])) {
                    $value = $record['col' . $column->id_column];
                    if ($column->type == CollectionColumn::TYPE_JSON) {
                        $value = Json::decode($value);
                    }
                    $this->_record[$column->alias] = $value;
                }
            }
        }

        return $this->_record;
    }
}
