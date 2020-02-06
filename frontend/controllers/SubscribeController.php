<?php

namespace frontend\controllers;

use common\models\Subscriber;
use frontend\models\SubscribeForm;
use yii\web\Controller;
use Exception;
use Yii;

class SubscribeController extends Controller
{
    /**
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {
        $subscribeForm = new SubscribeForm();
        if (Yii::$app->request->isPost) {
            $subscribeForm->load(Yii::$app->request->post());
            if ($subscribeForm->validate() && $subscribeForm->subscribe()) {
                //todo
                //   Yii::$app->session->setFlash('success', 'Вы успешно подписались на рассылку!');
            } else {
                //todo
                //   Yii::$app->session->setFlash('error', 'К сожалению не удалось подписаться на рассылку!');
            }
        }

        return $this->render('subscribe', ['subscribeForm' => $subscribeForm]);
    }

    /**
     * @param $token
     * @return string
     * @throws Exception
     */
    public function actionUnSubscribe($token)
    {
        $subscriber = Subscriber::findByToken($token);
        $unSub = false;
        if ($subscriber) {
            $subscriber->deleteAllSubscriptions();
            $unSub = true;
        }
        return $this->render('un-subscribe', [
            'unsub' => $unSub
        ]);
    }
}