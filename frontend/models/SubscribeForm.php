<?php

namespace frontend\models;

use common\models\News;
use common\models\Subscriber;
use Yii;
use yii\base\Model;
use Exception;

class SubscribeForm extends Model
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var array
     */
    public $subscribeSections = [];

    /**
     * @var boolean
     */
    public $isAllSubscribe;

    /**
     * @return bool
     * @throws Exception
     */
    public function subscribe()
    {
        if (is_string($this->subscribeSections)) {
            return false;
        }
        $subscriber = Subscriber::findByEmail($this->email);
        if ($this->isAllSubscribe) {
            $this->subscribeSections = array_flip(News::getUniqueNews());
        }
        if ($subscriber) {
            $pages = $subscriber->pagesIds;
            return $subscriber->createSubscriptions($this->diffSubscription($pages));
        }
        $subscriber = $this->createSubscribe();
        if ($subscriber->save()) {
            return $subscriber->createSubscriptions($this->subscribeSections);
        }
        return false;
    }

    /**
     * @return Subscriber
     * @throws Exception
     */
    private function createSubscribe()
    {
        $subscriber = new Subscriber();
        $subscriber->email = $this->email;
        $subscriber->access_token = Yii::$app->security->generateRandomString();
        if (Yii::$app->user->identity) {
            $subscriber->id_user = Yii::$app->user->identity->id;
        }
        return $subscriber;
    }

    /**
     * @param array $pages
     * @return array
     */
    private function diffSubscription($pages)
    {
        return array_diff($this->subscribeSections, $pages);
    }

    public function rules()
    {
        return [
            ['email', 'string'],
            [['email'], 'required'],
            [['subscribeSections'], 'each', 'rule' => ['integer']],
            ['isAllSubscribe', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'subscribeSections' => 'Разделы новостей',
            'isAllSubscribe' => 'Подписаться на все разделы новостей ?',
        ];
    }
}