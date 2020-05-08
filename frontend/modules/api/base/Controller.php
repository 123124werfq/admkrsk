<?php

namespace frontend\modules\api\base;

use common\models\Application;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\UnauthorizedHttpException;

class Controller extends \yii\rest\Controller
{
    /**
     * @return void
     * @throws UnauthorizedHttpException
     * @throws InvalidConfigException
     */
    public function checkAccess(): void
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
    }
}
