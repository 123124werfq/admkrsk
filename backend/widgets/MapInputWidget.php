<?php
namespace backend\widgets;

use yii\base\Widget;

class MapInputWidget extends Widget
{
    public $name;
    public $index;
    public $value;

    public function run()
    {
    	$coords = ['',''];

    	if (!empty($this->value) && is_array($this->value) && isset($this->value[0]) && isset($this->value[1]))
    		$coords = $this->value;

        return $this->render('mapinput', [
            'cid' => $this->index,
            'value' => $coords,
            'fname' => $this->name,
        ]);
    }
}
