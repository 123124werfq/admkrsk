<?php

namespace frontend\modules\api\controllers;

use frontend\modules\api\base\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Default controller for the `api` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        throw new ForbiddenHttpException();
    }
}
