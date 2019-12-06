<?php

namespace frontend\components;

use common\models\Action;
use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

use common\models\Page;
use common\models\News;
use common\models\ControllerPage;

use Yii;

class NewsUrlRule extends BaseObject implements UrlRuleInterface
{
    public function createUrl($manager, $route, $params)
    {
        $routes = Yii::$app->cache->getOrSet('routes', function () {
            return ControllerPage::find()->joinWith('page')->all();
        });

        $urls = [];

        foreach ($routes as $key => $data)
        {
            $urls[$data->controller.'/index'] = $data->page->getUrl();

            foreach (explode(',', $data->actions) as $akey => $action)
            {
                if ($action!='index')
                    $urls[$data->controller.'/'.$action] = $data->page->getUrl().'/'.$action;
            }
        }

        if (isset($urls[$route]))
            return $urls[$route].((!empty($params))?'?'.http_build_query($params):'');

        return false;
    }

    public function parseRequest($manager, $request)
    {
        $request = Yii::$app->request;
        $pathInfo = $request->getPathInfo();

        $routes = Yii::$app->cache->getOrSet('routes', function () {
            return ControllerPage::find()->joinWith('page')->all();
        });

        $urls = [];
        $pages = [];

        foreach ($routes as $key => $route)
        {
            $urls[$route->controller.'/index'] = substr($route->page->getUrl(),1);
            $pages[$route->controller.'/index'] = $route->page;

            $actions = explode(',', $route->actions);

            foreach ($actions as $akey => $action)
            {
                if ($action!='index' && !empty($action))
                {
                    $urls[$route->controller.'/'.$action] = substr($route->page->getUrl(),1).'/'.$action;
                    $pages[$route->controller.'/'.$action] = $route->page;
                }
            }
        }

        if ($route = array_search($pathInfo, $urls))
        {
            /*if (strpos($route, '/collection')>0 && !empty($_GET['id']))
                return ['collection/view',['id'=>$_GET['id'],'page'=>$pages[$route]]];*/

            return [$route,['page'=>$pages[$route]]];
        }

        $alias = explode('/', $request->url);
        $alias = array_pop($alias);

        if (strpos($alias, '?')>0)
            $alias = substr($alias, 0, strpos($alias, '?'));

        $page = Page::find()->where(['alias'=>$alias])->one();

        if (empty($page))
            return false;

        if ($page->noguest && Yii::$app->user->isGuest)
            return ['site/login',[]];

        $news_count = News::find()->where(['id_page'=>$page->id_page])->count();

        if ($news_count>0)
        {
            if (!empty(Yii::$app->request->get('id')))
                return ['news/view',['id'=>Yii::$app->request->get('id'),'page'=>$page]];
            else
                return ['news/index',['page'=>$page]];
        }

        return ['site/page', ['page'=>$page]];
    }
}