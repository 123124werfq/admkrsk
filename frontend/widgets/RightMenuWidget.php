<?php
namespace frontend\widgets;

use Yii;
use common\models\News;
use yii\helpers\Url;

class RightMenuWidget extends \yii\base\Widget
{
	public $page;

    public function run()
    {
    	if (empty($this->page))
    		return false;
    	
        $menu = $this->page->menu;

        // временно отключаем меню на главной странице личного кабинета для конкурсов
        if( ($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']) == 'grants.admkrsk.ru/personal')
            $menu = null;
var_dump($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        return $this->render('rightmenu',[
        	'page'=>$this->page,
        	'menu'=>$menu,
        ]);
    }
}
