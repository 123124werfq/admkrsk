<?php
namespace frontend\widgets;

use Yii;
use common\models\News;

class RightMenuWidget extends \yii\base\Widget
{
	public $page;

    public function run()
    {
    	if (empty($this->page))
    		return false;
    	
        $menu = $this->page->menu;

        return $this->render('rightmenu',[
        	'page'=>$this->page,
        	'menu'=>$menu,
        ]);
    }
}
