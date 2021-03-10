<?php
namespace frontend\widgets;

use yii\web\AssetBundle;

class FormAssets extends AssetBundle
{
    public $js = [
        'js/form.js?v=3'
    ];

    public $css = [
         // CDN lib
    ];

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . "/views/form/assets";
        parent::init();
    }
}
?>