<?php
namespace frontend\widgets;

use Yii;
use common\models\Page;

class Block extends \yii\base\Widget
{
	public $page;
	public $block;

    public function run()
    {
    	$blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

        return $this->render('blocks/'.$this->block->type,[
        	'blockVars'=>$blockVars,
        ]);
    }
}
