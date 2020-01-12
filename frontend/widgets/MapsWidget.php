<?php
namespace frontend\widgets;

use yii\base\Widget;
use common\models\Collection;

class MapsWidget extends Widget
{
    public $page;
    public $block;

    public function run()
    {
        if (!empty($this->block))
        {
            $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

            if (empty($blockVars['collections']->value))
                return false;

            $ids = $blockVars['collections']->value;
        }

        $collections = Collection::find()->where(['id_collection'=>$ids])->all();

        return $this->render('block/maps',[
            'collections' => $collections,
            'page'=>$page
        ]);
    }
}
