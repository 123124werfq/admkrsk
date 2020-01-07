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
        $pages[] = $this->page;

        $output[] = '<li class="breadcrumbs_item"><a href="/">Главная</a></li>';

        $path = '';

        $domain = '';
        foreach ($pages as $key => $page)
        {
            $path .= '/'.$page->alias;

            if ($page->is_partition && !empty($page->domain))
            {
                $url = $domain = $page->domain;
                $output = [];
            }
            else
                $url = $domain.$path;

            if ($page->active == 1)
                $output[] = '<li class="breadcrumbs_item"><a href="'.$url.'">'.$page->title.'</a></li>';
        }

        return '<ol class="breadcrumbs">'.implode('',$output).'</ol>';
    }
}
