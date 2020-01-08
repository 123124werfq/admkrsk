<?php
namespace frontend\widgets;

use Yii;
use common\models\Page;

class Breadcrumbs extends \yii\base\Widget
{
    public $page;

    public function run()
    {
        if (empty($this->page))
            return false;

        $pages = $this->page->parents()->all();
        array_shift($pages);

        $pages[] = $this->page;

        $homeTitle = (Yii::$app->language == 'en')?'Home':'Главная';

        $output[] = '<li class="breadcrumbs_item"><a href="/">'.$homeTitle.'</a></li>';

        $partition_domain = $path = '';

        foreach ($pages as $key => $page)
        {
            $path .= '/'.$page->alias;

            if ($page->is_partition && !empty($page->partition_domain))
            {
                $url = $partition_domain = $page->partition_domain;
                $output = [];
            }
            else
                $url = $partition_domain.$path;

            if ($page->active == 1)
                $output[] = '<li class="breadcrumbs_item"><a href="'.$url.'">'.$page->title.'</a></li>';
        }

        return '<ol class="breadcrumbs">'.implode('',$output).'</ol>';
    }
}
