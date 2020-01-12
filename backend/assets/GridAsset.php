<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Grid bundle
 */
class GridAsset extends AssetBundle
{
    public $css = [
        '/css/datePicker/daterangepicker.min.css',
        '/css/grid.css',
    ];
    public $js = [
        '/js/gridSettings.js',
        '/js/datePicker/moment.min.js',
        '/js/datePicker/jquery.daterangepicker.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\jui\JuiAsset',
    ];
}