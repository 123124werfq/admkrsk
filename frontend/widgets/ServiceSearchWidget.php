<?php

namespace frontend\widgets;

use common\models\Service;
use common\models\Menu;
use yii\base\Widget;

class ServiceSearchWidget extends Widget
{
    public $block;
    public $page;

    public function run()
    {
        if (empty($this->block))
            return false;

        $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

        $menu = false;

        if (!empty($blockVars['services']))
            $menu = Menu::findOne($blockVars['services']->value);

        $onlineCount = Service::find()->where(['online'=>1])->count();

        return $this->render('service_search',[
            'blockVars' => $blockVars,
            'menu'=>$menu,
            'onlineCount'=>$onlineCount,
        ]);
    }
}
