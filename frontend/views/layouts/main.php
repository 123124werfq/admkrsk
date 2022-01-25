<?php
use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;

$bundle = AppAsset::register($this);

$accessabilityMode = (isset($_COOKIE['accessabilityMode']) && $_COOKIE['accessabilityMode']=='true')?true:false;

//var_dump($this->context);

$layout_header = $layout_footer = null;

if (!empty($this->params['page']))
{
    $page = $this->params['page'];

    if ($page->is_partition && !empty($page->blocksLayout))
        $layout = $page;
    else
        $layout = $page->parents()
            ->joinWith('blocksLayout')
            ->andWhere('is_partition = TRUE AND db_block.id_block IS NOT NULL')
            ->orderBy('depth DESC')
            ->one();

    if (!empty($layout))
    {
        $layouts = $layout->getBlocksLayout()->indexBy('type')->all();

        if (!empty($layouts['header']))
            $layout_header = $layouts['header']->getBlockVars()->indexBy('alias')->all();

        if (!empty($layouts['footer']))
            $layout_footer = $layouts['footer']->getBlockVars()->indexBy('alias')->all();
    }
}
?>
<?php $this->beginPage() ?>
<!doctype html>
<html class="no-js" lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <meta name="HandheldFriendly" content="true">
    <link href="<?= $bundle->baseUrl . '/css/accessability.css' ?>" rel="stylesheet accessability" type="text/css" <?=$accessabilityMode?'':'disabled'?> title="accessability">

    <link href="<?= $bundle->baseUrl . '/css/site.css?v=2' ?>" rel="stylesheet" type="text/css" <?=$accessabilityMode?'disabled':''?>>

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

    <?php if (!empty($layouts['header']->state)){
            echo $this->render('_header', ['bundle' => $bundle,'header'=>$layout_header])?>
    }?>
    <div class="page">
        <div class="svg-hidden">
            <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
            <filter id="svgred">
                    <feColorMatrix
                      type="matrix"
                      values="0 0 0 0 0.8
                              0 0.16 0 0 0
                              0 0 0.18 0 0
                              0 0 0 1 0 "/>
                </filter>
            </svg>
            <svg xmlns="http://www.w3.org/2000/svg" width="0" height="0">
                <filter id="border-red">
                    <feColorMatrix
                      type="matrix"
                      values="1 0 0 0 0
                              0 0 0 0 0
                              0 0 0 0 0
                              0 0 0 1 0 "/>
                </filter>
            </svg>
        </div>
        <?=Alert::widget()?>
        <?=$content?>
        <?php if (!empty($layouts['footer']->state)){
            echo $this->render('_footer', ['bundle' => $bundle,'footer'=>$layout_footer])?>
        }?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>