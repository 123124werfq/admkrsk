<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string $class
 * @property int $message
 * @property int $repeat_notify
 * @property int $main_notify
 * @property int $created_at
 * @property int $updated_at
 * @property-read int $repeatNotifyTime
 * @property-read int $mainNotifyTime
 */
class Notify extends ActiveRecord
{
    const COLLECTION = 'список';

    const PAGE = 'раздел';

    /**
     * @param string $class
     * @return string|null
     */
    public static function getNotifyNameByClass($class)
    {
        switch ($class) {
            case Collection::class:
                return static::COLLECTION;
            case Page::class:
                return static::PAGE;
            default :
                return null;
        }
    }

    /**
     * @param string $class
     * @return ActiveRecord|null
     */
    public static function getNotifyRuleByClass($class)
    {
        return static::find()
            ->where([
                'class' => $class
            ])
            ->limit(1)
            ->one();
    }

    public function getRepeatNotifyTime()
    {
        return $this->mapTime($this->repeat_notify);
    }

    public function getMainNotifyTime()
    {
        return $this->mapTime($this->main_notify);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notify_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['repeat_notify', 'main_notify'], 'integer'],
            [['class', 'message'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'ts' => [
                'class' => TimestampBehavior::class,
            ],
        ];
    }

    /**
     * Map receive time from frontend
     *
     * @param int $time
     * @return string|null
     */
    private function mapTime($time)
    {
        switch ($time) {
            case 1:
                return '30 minutes';
            case 2:
                return '1 hour';
            case 3:
                return '3 hour';
            default:
                return null;
        }
    }
}