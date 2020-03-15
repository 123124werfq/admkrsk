<?php

namespace backend\widgets;

use common\models\Page;
use common\models\News;
use yii\base\Widget;
use Yii;

class NavMenuWidget extends Widget
{
    public function run()
    {
        $menu = require __DIR__ . '/../config/menu.php';

        $newsRoles = $menu['news']['roles'] ?? null;

        if (!empty($newsRoles) && Yii::$app->user->identity->can($newsRoles))
        {
            $news_pages = Page::find()
                ->andFilterWhere(['id_page' => Page::getAccessPageIds()])
                ->andWhere(['or',['type'=>Page::TYPE_NEWS],['type'=>Page::TYPE_ANONS]])
                ->all();

            if (!empty($news_pages)) {

                $menu['news']['submenu'] = [];

                foreach ($news_pages as $key => $page) {
                    $menu['news']['submenu']['news?id_page=' . $page->id_page] = [
                        'title' => $page->title,
                        'roles' => [
                            'backend.entityAccess' => [
                                'entity_id' => $page->id_page,
                                'class' => Page::class,
                            ],
                        ],
                    ];
                }
            }
        }

        return $this->render('navmenu', [
            'menu' => $menu,
            'user' => Yii::$app->user->identity,
            'active_url' => ltrim(str_replace('/master/', '', Yii::$app->request->url), '/')
        ]);
    }
}
