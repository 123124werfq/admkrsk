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
        "js/jquerybuilder/css/query-builder.default.min.css",
        "css/admin.css?v=6"
    ];
    public $js = [
        "https://api-maps.yandex.ru/2.1/?lang=ru_RU",
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
        "js/jquerybuilder/query-builder.standalone.min.js",
        "js/jquerybuilder/i18n/query-builder.ru.js",
        "js/admin.js?v=15",
        
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
//        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
    ];
}

