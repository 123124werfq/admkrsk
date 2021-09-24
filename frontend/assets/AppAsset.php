<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    //public $sourcePath = '@frontend/assets/app';
    /*public $css = [
        'css/style.css',
        'css/site.css',
    ];*/

    public $js = [
        '//yastatic.net/es5-shims/0.0.2/es5-shims.min.js',
        '//yastatic.net/share2/share.js',
        'js/polyfill.js',
        'js/plyr.js',
        'js/slick.min.js',
        'js/jquery.countdown.min.js',
        'js/chart.min.js',
        'js/chartjs-plugin-datalabels.min.js',
        'js/jquery.fancybox.min.js',
        'js/jquery-ui.min.js',
        'js/tipso.min.js',
        'js/moment-with-locales.min.js',
        'js/jquery.daterangepicker.min.js',
        'js/main.js',
        'js/site.js?v=17',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
