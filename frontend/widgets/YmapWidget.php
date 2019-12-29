<?php

namespace frontend\widgets;

use yii\base\Widget;

class YmapWidget extends Widget
{
    public $points;
    public $options;

    public function run()
    {
        $defaultOptions = [
            'width' => '100%',
            'height' => '300px',
            'zoom' => 12,
            'center_x' => '56.010563',
            'center_y' => '92.852572'
        ];

        $this->options = array_replace($this->options, $defaultOptions);

        return $this->render('map',[
            'points' => $this->points,
            'options' => $this->options
        ]);
    }
}
