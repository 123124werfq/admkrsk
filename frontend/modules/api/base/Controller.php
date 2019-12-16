<?php

namespace frontend\modules\api\base;

use common\models\Application;
use Yii;
use yii\web\UnauthorizedHttpException;

class Controller extends \yii\rest\Controller
{
    /**
     * @param $action
     * @return bool
     * @throws UnauthorizedHttpException
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action)
    {
        $authHeader = null;

        if (preg_match(
            '/^Bearer\s+(.*?)$/',
            Yii::$app->request->getHeaders()->get('Authorization'),
            $matches
        )) {
            $authHeader = $matches[1];
        }

        $query = Application::find()
            ->where([
                'is_active' => true,
                'access_token' => $authHeader,
            ]);

        if (!isset($authHeader) || !$query->exists()) {
            throw new UnauthorizedHttpException('Your request was made with invalid credentials.');
        }

        return parent::beforeAction($action);
    }
}
