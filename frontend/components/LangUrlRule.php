<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

use Yii;


class LangUrlRule extends BaseObject implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    public function parseRequest($manager, $request)
    {

        $request = Yii::$app->request;
        $pathInfo = $request->getPathInfo();

        //$parts = explode("/", $pathInfo);

        if(strpos(" ".$pathInfo, "en/")==1)
            Yii::$app->language = 'en';


        return false;  // данное правило не применимо
    }
}