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

        $path = explode('/', $this->page->path);

        $pages = Page::find()->where(['id_page'=>$path])->indexBy('id_page')->all();

        $output = ' <ol class="breadcrumbs">
                    <li class="breadcrumbs_item"><a href="/">Главная</a></li>';

        $url = '';

        foreach ($path as $key => $data)
        {
            if (!empty($pages[$data]))
            {
                $url .= '/'.$pages[$data]->alias;

                $output .= '<li class="breadcrumbs_item"><a href="'.$url.'">'.$pages[$data]->title.'</a></li>';
            }
        }
        $output .= '</ol>';

        return $output;
    }
}
