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
        if (!empty($this->urls))
            return $this->urls;

        $this->urls = Yii::$app->cache->getOrSet('route_urls', function (){

            $controllers =  ControllerPage::find()->joinWith('page')->all();

            $urls = [];

            foreach ($controllers as $key => $data)
            {
                // index action for direct url
                $urls[$data->controller.'/index'] = [
                    'url'=>$data->page->getUrl(),
                    'page'=>$data->page->id_page,
                ];

                // action urls
                foreach (explode(',', $data->actions) as $akey => $action)
                {
                    if ($action!='index')
                        $urls[$data->controller.'/'.$action] = [
                            'url'=>$data->page->getUrl().'/'.$action,
                            'page'=>$data->page->id_page,
                        ];
                }
            }

            return $urls;
        });

        return $this->urls;
    }

    protected function findRouteByURL($url)
    {
        $routes = $this->getRoutes();

        foreach ($routes as $key => $route)
        {
            if ($route['url']==$url)
                return [
                    'route'=>$key,
                    'page'=>Page::findOne($route['page'])
                ];
        }

        return false;
    }

    public function createUrl($manager, $route, $params)
    {
        $routes = $this->getRoutes();

        if (isset($routes[$route]))
            return $routes[$route]['url'].((!empty($params))?'?'.http_build_query($params):'');

        return false;
    }

    public function parseRequest($manager, $request)
    {
        $request = Yii::$app->request;
        $pathInfo = $request->getPathInfo();
        $domain = \yii\helpers\Url::base(true);

        $routes = $this->getRoutes();

        // если обратились к корню то проверяем от какого раздела этот домен
        if (empty($pathInfo))
        {
            // проверяем по зарезервированным путям
            $route = $this->findRouteByURL($domain);
            if (!empty($route))
                return [$route['route'], ['page'=>$route['page']]];

            // ищем страницу по домену
            $domainPage = Page::find()
                ->where([
                    'is_partition' => true,
                    'active'=>1
                ])
                ->andWhere(['partition_domain'=>$domain])
                ->one();

            if (!empty($domainPage))
                return ['site/page', ['page'=>$domainPage]];
        }

        /*$urls = [];
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
        }*/

        // ищем из резервированных
        if ($route = $this->findRouteByURL($domain.$pathInfo))
            return [$route['route'], ['page'=>$route['page']]];

        // вычленяем последний кусок от урла
        $alias = explode('/', $pathInfo);
        $alias = array_pop($alias);

        if (strpos($alias, '?')>0)
            $alias = substr($alias, 0, strpos($alias, '?'));

        $page = Page::find()->where(['alias'=>$alias, 'active'=>1])->one();
        if (empty($page))
            return false;

        if ($page->noguest && Yii::$app->user->isGuest)
            return ['site/login',[]];

        // проверяем новостная ли эта страница
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