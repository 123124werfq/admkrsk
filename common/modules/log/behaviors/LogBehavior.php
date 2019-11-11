<?php

namespace common\modules\log\behaviors;

use common\modules\log\models\Log;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;

/**
 * @property ActiveRecord $owner
 */
class LogBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveLog',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveLog',
            ActiveRecord::EVENT_AFTER_DELETE => 'saveLog',
        ];
    }

    /**
     * @param AfterSaveEvent $event
     * @return bool
     */
    public function saveLog($event)
    {
        if ($this->isAttributesChanged($event)) {
            $log = new Log();

            if (Yii::$app instanceof \yii\console\Application) {
                $log->detachBehavior('ba');
            }

            $log->model = get_class($this->owner);
            $log->model_id = $this->owner->primaryKey;
            $log->previous_id = $this->getPreviousId($log);
            $log->data = $this->filterAttributes($this->owner->attributes);

            return $log->save();
        }

        return false;
    }

    /**
     * @param Log $log
     * @return false|string|null
     */
    private function getPreviousId(Log $log)
    {
        return Log::find()->select(['id'])->where(['model' => $log->model, 'model_id' => $log->model_id])->orderBy(['id' => SORT_DESC])->scalar() ?: null;
    }

    /**
     * @param array $attributes
     * @return array
     */
    private function filterAttributes($attributes)
    {
        foreach (['created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'] as $attribute) {
            unset($attributes[$attribute]);
        }

        return $attributes;
    }

    /**
     * @param $event
     * @return bool
     */
    private function isAttributesChanged($event)
    {
        if ($event->name == 'afterDelete') {
            return true;
        }

        $changedAttributes = [];
        foreach ($event->changedAttributes as $attribute => $value) {
            if ($this->owner->attributes[$attribute] != $value) {
                $changedAttributes[$attribute] = $value;
            }
        }

        return (bool) count($this->filterAttributes($changedAttributes));
    }
}
