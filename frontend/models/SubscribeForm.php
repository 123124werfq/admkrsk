<?php

namespace frontend\models;

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
        $subscriber = Subscriber::findByEmail($this->email);
        if ($subscriber) {
            $pages = $subscriber->pagesIds;
            return $subscriber->createSubscriptions($this->diffSubscription($pages));
        }
        $subscriber = new Subscriber();
        $subscriber->email = $this->email;
        $subscriber->access_token = Yii::$app->security->generateRandomString();
        if (Yii::$app->user->identity) {
            $subscriber->id_user = Yii::$app->user->identity->id;
        }
        if ($subscriber->save()) {
            return $subscriber->createSubscriptions($this->subscribeSections);
        }
        return false;
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
            [['email', 'subscribeSections'], 'required'],
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