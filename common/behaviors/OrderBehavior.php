<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\db\Query;

class OrderBehavior extends Behavior
{
    /**
     * @var array Атрибут сортировки
     */
    public $attribute = 'ord';

    /**
     * @var array Список атрибутов для фильтрации
     */
    public $filters = [];

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'setValue',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'setValue',
        ];
    }

    /**
     * Форматироет дату
     * @param Event $event
     */
    public function setValue($event)
    {
        $attribute = $this->attribute;

        if ($this->owner->$attribute === null || $this->owner->$attribute === '') {
            $query = (new Query())
                ->from($this->owner->tableName());

            if ($this->filters) {
                foreach ($this->filters as $filter) {
                    $query->andFilterWhere(['[[' . $filter . ']]' => $this->owner->$filter]);
                }
            }

            $order = $query->max('[[' . $this->attribute . ']]');

            $this->owner->$attribute = $order !== null ? $order + 1 : 0;
        }
    }
}
