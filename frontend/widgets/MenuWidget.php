<?php
namespace frontend\widgets;

use Yii;
use common\models\Menu;

class MenuWidget extends \yii\base\Widget
{
	public $alias;
    public $block;
    public $page;
	public $template = false;

    public function run()
    {
        if (!empty($this->alias))
        {
    	   $menu = Menu::find()->where(['alias'=>$this->alias])->one();
        }
        elseif (!empty($this->block))
        {
            $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

            if (empty($blockVars['menu']))
                return '';

            $menu = Menu::find()->where(['id_menu'=>$blockVars['menu']->value])->one();

            if (!empty($blockVars['template']))
                $this->template = $blockVars['template']->value;
        }

    	if (empty($menu))
    		return false;

    	if (!empty($this->template))
    		return $this->render('menu/'.$this->template,[
	        	'menu'=>$menu,
	        ]);

    	switch ($menu->type) {
    		case Menu::TYPE_LIST:
    			$template = 'menu_list';
    			break;
    		case Menu::TYPE_TABS:
    			$template = 'menu_tabs';
    			break;
    		case Menu::TYPE_LEVELS:
    			return '';
    			break;
    		default:
    			$template = 'menu_list';
    			break;
    	}

        return $this->render('menu/'.$template,[
        	'menu'=>$menu,
        ]);
    }
}
