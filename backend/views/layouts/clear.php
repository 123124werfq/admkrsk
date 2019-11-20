<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use common\widgets\Alert;

$bundle = AppAsset::register($this);
$this->beginPage();
?><!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрация</title>
    <?php $this->head()?>
    <?=Html::csrfMetaTags() ?>
</head>
<body style="background: #fff; padding: 20px 20px 100px; width: 1000px; height: 600px;">
<?php $this->beginBody(); ?>
<?=$content?>
<?php $this->endBody();?>
</body>
</html>
<?php $this->endPage();?>