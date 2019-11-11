<?php
namespace backend\widgets;

use common\models\Page;
use yii\base\Widget;
use Yii;

class MapInputWidget extends Widget
{
    public $name;
    public $index;

    public function run()
    {

        return $this->render('mapinput', [
            'cid' => $this->index,
            'fname' => $this->name
        ]);
    }
}
