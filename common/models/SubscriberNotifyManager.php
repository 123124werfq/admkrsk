<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;

/**
 * This class manage subscribers notify
 *
 * @property integer $id
 * @property integer $subscriber_id
 * @property integer $page_id
 * @property integer $news_id
 * @property boolean $isNotify
 * @property integer $created_at
 * @property integer $notify_at
 */
class SubscriberNotifyManager extends ActiveRecord
{
    /**
     * TODO
     *  Функцию по логгированию тоже необхоидмо добавить в job.
     *  Т.к если пользователей будет много, процедура записи
     *  будет очень долгой.
     *
     * Logging subscriber's notify
     * @param News $model
     */
    public function loggingNotify($model)
    {
        $query = (new Query())
            ->from('subscriber_subscriptions')
            ->where(['page_id' => $model->id_page]);
        foreach ($query->batch(500) as $subscribers) {
            foreach ($subscribers as $subscriber) {
                $subNotify = new static();
                $subNotify->subscriber_id = $subscriber['subscriber_id'];
                $subNotify->page_id = $model->id_page;
                $subNotify->news_id = $model->id_news;
                $subNotify->save(false);
            }
        }
    }

    /**
     * TODO
     *  После отправки пиьсма
     *  установите `isNotify` = true
     */
    public function notify()
    {
        //todo создайте Job здесь
    }

    public static function tableName()
    {
        return 'subscribers_notify';
    }

    public function rules()
    {
        return [
            [['id', 'subscriber_id', 'page_id', 'news_id', 'created_at', 'notify_at'], 'integer'],
            [['isNotify',], 'boolean'],
        ];
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'updatedAtAttribute' => false,
            ]
        ];
    }
}