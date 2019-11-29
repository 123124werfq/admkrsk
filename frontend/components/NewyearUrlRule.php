<?php

namespace frontend\components;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

use common\models\Page;

use Yii;

class NewyearUrlRule extends BaseObject implements UrlRuleInterface
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

        if(!strpos($hostInfo, 'newyear.admkrsk'))
            return false;

        if(strlen($pathInfo) && !strpos($pathInfo, 'new-year2019') && !strpos($pathInfo, 'event/program'))
            \Yii::$app->response->redirect('http://www.admkrsk.ru', 301);


//        \Yii::$app->response->redirect('/m/dashboard', 301);
/*
        $alias = explode('/', $request->url);
        $alias = array_pop($alias);

        if (strpos($alias, '?')>0)
            $alias = substr($alias, 0, strpos($alias, '?'));
*/


        $page = Page::find()->where(['alias' => 'new-year2019'])->one();

        if(!$page)
            return false;

        return ['site/page', ['page'=>$page]];
    }
}