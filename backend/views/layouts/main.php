<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use backend\widgets\NavMenuWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

$bundle = AppAsset::register($this);

$this->registerJs("$('a.navbar-minimalize').on('click', function(event) {
    console.log($('body').hasClass('mini-navbar'));
    $.ajax({
        method: 'post',
        url: '" . Url::to(['site/setting']) . "',
        data: { mininavbar: +$('body').hasClass('mini-navbar') }
    });
})");
$this->beginPage();
?><!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Администрация</title>
    <?php $this->head()?>
    <?=Html::csrfMetaTags() ?>
</head>
<body class="<?= Yii::$app->request->cookies->getValue('mininavbar', false) ? 'mini-navbar' : '' ?>" data-spy="scroll" data-target="#navbar">
<?php
    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
        $script = "toastr.$type('$message', '');";
        $this->registerJs($script, yii\web\View::POS_END);
    }
    $this->registerJs("var tinymce_plugins = '".implode(' ',Yii::$app->params['tinymce_plugins'])."';", yii\web\View::POS_BEGIN);
?>
<?php $this->beginBody(); ?>
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <?= NavMenuWidget::widget() ?>
        </div>
    </nav>
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <!--<form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>-->
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li><?= Html::a('<i class="fa fa-sign-out"></i> Выйти', ['site/logout'], ['data' => ['method' => 'post']]) ?></li>
                </ul>
            </nav>
        </div>
        <?php if (!empty($this->params['breadcrumbs'])) { ?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-6">
                    <h2><?= $this->title ?></h2>
                    <?=Breadcrumbs::widget([
                        'homeLink'=> ['label' => 'Главная', 'url' => ['/master']],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="col-lg-6 text-right button-block">
                    <?php if (isset($this->params['action-block'])){?>
                    <div class="dropdown">
                      <button class="btn btn-default dropdown-toggle" type="button" id="actionDropDown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Действия
                        <span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="actionDropDown">
                        <?php foreach ($this->params['action-block'] as $key => $link) {
                            echo "<li>$link</li>";
                        }?>
                      </ul>
                    </div>
                    <?php }?>
                    <?= isset($this->params['button-block']) ? implode(' ', $this->params['button-block']) : '' ?>
                </div>
            </div>
        <?php } ?>
        <div class="wrapper wrapper-content">
            <?= $content ?>
        </div>
    </div>
</div>

<div id="redactor-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<div id="dashboard-modal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
            <h3>Добавить текущую страницу в быстрый доступ?</h3>
            <div class="form-group">
                <label class="control-label" for="dash-link">Ссылка</label>
                <input id="dash-link" class="form-control" name="dash-link" value="">
            </div>
            <div class="form-group">
                <label class="control-label" for="dash-name">Подпись</label>
                <input id="dash-name" class="form-control" name="dash-name" value="">
            </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary" id="dash-save">Добавить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<div id="CollectionRecord" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary">Сохранить</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

<?php $this->endBody();?>

<div id="right-sidebar" class="animated">
    <div class="sidebar-container">
      <?=Yii::$app->params['sidebar']??''?>
    </div>}
</div>

</body>
</html>
<?php $this->endPage();?>

