<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;

$bundle = AppAsset::register($this);

$this->beginPage();
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрация</title>
    <?php $this->head()?>
    <?=Html::csrfMetaTags() ?>
</head>
<body class="gray-bg">
<?php $this->beginBody(); ?>
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <?= $content ?>
    </div>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>

