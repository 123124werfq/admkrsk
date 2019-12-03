<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

use Yii;

class PravoUrlRule extends BaseObject implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {
        return false;
    }

    public function parseRequest($manager, $request)
    {
        $request = Yii::$app->request;
        $hostInfo = $request->getHostInfo();
        $pathInfo = $request->getPathInfo();

        if(!strpos($hostInfo, 'pravo.admkrsk') && !strpos($hostInfo, 't3.admkrsk'))
            return false;

//        \Yii::$app->response->redirect('/m/dashboard', 301);

        $alias = explode('/', $request->url);
        $alias = array_pop($alias);

        if (strpos($alias, '?')>0)
            $alias = substr($alias, 0, strpos($alias, '?'));

        return ['pravo/index', [] ];

      //  return ['site/page', ['page'=>$page]];
    }
}