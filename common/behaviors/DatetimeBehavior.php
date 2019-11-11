<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use yii\validators\NumberValidator;

class DatetimeBehavior extends Behavior
{
    /**
     * @var array Список атрибутов
     */
    public $attributes = [];

    /**
     * @var string Формат даты
     */
    public $format = 'dd.MM.yyyy HH:mm:ss';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->attributes = [
            BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attributes,
            BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attributes,
            BaseActiveRecord::EVENT_AFTER_INSERT => $this->attributes,
            BaseActiveRecord::EVENT_AFTER_UPDATE => $this->attributes,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function events()
    {
        return array_fill_keys(
            array_keys($this->attributes),
            'setValue'
        );
    }

    /**
     * Форматироет дату
     * @param Event $event
     * @throws \yii\base\InvalidConfigException
     */
    public function setValue($event)
    {
        if (in_array($event->name, [BaseActiveRecord::EVENT_BEFORE_INSERT, BaseActiveRecord::EVENT_BEFORE_UPDATE])) {
            foreach ($this->attributes[$event->name] as $attribute) {
                $validator = new NumberValidator(['integerOnly' => true]);

                if (!$validator->validate($this->owner->$attribute)) {
                    $this->owner->$attribute = Yii::$app->formatter->asTimestamp($this->owner->$attribute);
                }
            }
        }

        if (in_array($event->name, [BaseActiveRecord::EVENT_AFTER_INSERT, BaseActiveRecord::EVENT_AFTER_UPDATE])) {
            foreach ($this->attributes[$event->name] as $attribute) {
                $validator = new NumberValidator(['integerOnly' => true]);

                if ($validator->validate($this->owner->$attribute)) {
                    $this->owner->$attribute = Yii::$app->formatter->asDatetime($this->owner->$attribute, $this->format);
                }
            }
        }
    }
}
