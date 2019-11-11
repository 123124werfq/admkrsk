<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    //public $sourcePath = '@backend/assets/app';

    public $css = [
        "inspinia/font-awesome/css/font-awesome.css",
        "inspinia/css/animate.css",
        "inspinia/css/plugins/datapicker/datepicker3.css",
        "inspinia/css/style.css",
        'inspinia/css/plugins/toastr/toastr.min.css',
        "inspinia/css/plugins/select2/select2.min.css",
        'inspinia/css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css',
        "inspinia/css/plugins/jasny/jasny-bootstrap.min.css",
        "css/admin.css?1"
    ];
    public $js = [
        "inspinia/js/plugins/metisMenu/jquery.metisMenu.js",
        "inspinia/js/plugins/slimscroll/jquery.slimscroll.min.js",
        "inspinia/js/plugins/peity/jquery.peity.min.js",
        "inspinia/js/plugins/masonary/masonry.pkgd.min.js",
        "inspinia/js/inspinia.js",
        "inspinia/js/plugins/pace/pace.min.js",
        "inspinia/js/plugins/datapicker/bootstrap-datepicker.js",
        "inspinia/js/plugins/toastr/toastr.min.js",
        "inspinia/js/plugins/dataTables/datatables.min.js",
        "inspinia/js/bootstrap.min.js",
        "inspinia/js/plugins/jasny/jasny-bootstrap.min.js",
        "/js/tinymce/tinymce.min.js",
        "/js/tinymce/plugins/plugins.js",
        "js/admin.js"
//        'js/select2/js/select2.js', // если миша захочет раскомментить, надо сначала файл туда положить
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
    ];
}

