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
    public $urls;

    protected function getRoutes()
    {
        $this->urls = Yii::$app->cache->getOrSet('route_urls', function (){

            $controllers =  ControllerPage::find()->joinWith('page')->all();

            $urls = [];

            foreach ($controllers as $key => $data)
            {
                $urls[$data->controller.'/index'] = $data->page->getUrl();

                foreach (explode(',', $data->actions) as $akey => $action)
                {
                    if ($action!='index')
                        $urls[$data->controller.'/'.$action] = $data->page->getUrl().'/'.$action;
                }
            }

            return $urls;
        });

        return $this->urls;
    }

    public function createUrl($manager, $route, $params)
    {
        $urls = $this->getRoutes();

        if (isset($urls[$route]))
            return $urls[$route].((!empty($params))?'?'.http_build_query($params):'');

        return false;
    }

    public function parseRequest($manager, $request)
    {
        $request = Yii::$app->request;
        $pathInfo = $request->getPathInfo();
        $domain = \yii\helpers\Url::base(true);

        // если обратились к корню то проверяем домены
        if (empty($pathInfo))
        {
            $domains = Page::find()->where([
                'is_partition' => true,
                'active'=>1
            ])->andWhere('partition_domain IS NOT NULL')
            ->indexBy('partition_domain')->all();

            if (!empty($domains[$domain]))
                return ['site/page', ['page'=>$domains[$domain]]];
        }

        $routes = Yii::$app->cache->getOrSet('routes', function (){
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
                    $urls[$route->controller.'/'.$action] = $route->page->getUrl().'/'.$action;
                    $pages[$route->controller.'/'.$action] = $route->page;
                }
            }
        }

        if ($route = array_search($domain.$pathInfo, $urls))
            return [$route,['page'=>$pages[$route]]];

        $alias = explode('/', $pathInfo);
        $alias = array_pop($alias);

        if (strpos($alias, '?')>0)
            $alias = substr($alias, 0, strpos($alias, '?'));

        $page = Page::find()->where(['alias'=>$alias, 'active'=>1])->one();

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