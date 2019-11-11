<?php
namespace frontend\widgets;

use Yii;
use common\models\Project;
use common\models\Page;

class ProjectWidget extends \yii\base\Widget
{
	public $page;
    public $block;

    public function run()
    {
        $blockVars = $this->block->getBlockVars()->indexBy('alias')->all();

        if (empty($blockVars['id_page']))
            return '';

        $projects = Project::find()->all();
        $page = Page::findOne(['id_page'=>$blockVars['id_page']->value]);

        if (empty($projects) || empty($page))
            return '';

        return $this->render('projects',[
        	'projects'=>$projects,
            'page'=>$page,
            'blockVars'=>$blockVars,
        ]);
    }
}
