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
        if (Yii::$app->user->isGuest)
            return '';

        $menu = require __DIR__ . '/../config/menu.php';

        return $this->render('navmenu', [
            'menu' => $menu,
            'user' => Yii::$app->user->identity,
            'active_url' => ltrim(str_replace('/master/', '', Yii::$app->request->url), '/')
        ]);
    }
}
