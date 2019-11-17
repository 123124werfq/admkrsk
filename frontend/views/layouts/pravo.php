<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

$bundle = AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!doctype html>
    <html class="no-js" lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">


        <!-- Favicons -->
        <link rel="apple-touch-icon" href="<?= $bundle->baseUrl . '/favicon-152x152.png' ?>" sizes="152x152">
        <link rel="icon" href="<?= $bundle->baseUrl . '/favicon-32x32.png' ?>" sizes="32x32" type="image/png">
        <link rel="icon" href="<?= $bundle->baseUrl . '/favicon-16x16.png' ?>" sizes="16x16" type="image/png">
        <link rel="icon" href="<?= $bundle->baseUrl . '/favicon.ico' ?>">
        <link rel="icon" type="image/x-icon" href="<?= $bundle->baseUrl . '/favicon.ico' ?>">
        <meta name="theme-color" content="#3b4256">

        <link rel="shortcut icon" href="<?= $bundle->baseUrl . '/favicon.ico' ?>" type="image/x-icon">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta content="telephone=no" name="format-detection">
        <!-- This make sence for mobile browsers. It means, that content has been optimized for mobile browsers -->
        <meta name="HandheldFriendly" content="true">
        <link href="<?= $bundle->baseUrl . '/css/accessability.css' ?>" rel="stylesheet" type="text/css" disabled title="accessability">
        <link href="<?= $bundle->baseUrl . '/css/style.css' ?>" rel="stylesheet" type="text/css">

        <script>
            (function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)
        </script>

        <!--[if lt IE 9 ]>
        <script src="<?= $bundle->baseUrl . '/js/html5shiv-3.7.2.min.js' ?>" type="text/javascript"></script>
        <meta content="no" http-equiv="imagetoolbar">
        <![endif]-->

        <?php $this->head() ?>
    </head>

    <body>
    <?php $this->beginBody() ?>
    <?= $this->render('_header-pravo', ['bundle' => $bundle])?>
    <div class="page">
        <div class="main">
            <div class="container">
                <?= $content ?>
            </div>
        </div>
        <?= $this->render('_footer-pravo', ['bundle' => $bundle])?>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>