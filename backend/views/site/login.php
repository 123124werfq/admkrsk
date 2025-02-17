<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$bundle = AppAsset::register($this);

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">

    <div class="logo" style="padding-bottom: 30px;">
        <?= Html::img($bundle->baseUrl . '/img/logo-invert.svg', ['style' => 'width: 50%;'])?>
    </div>

    <h3><?= Html::encode($this->title) ?></h3>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username')->label(false)->textInput(['placeholder' => $model->getAttributeLabel('username'), 'autofocus' => true]) ?>

        <?= $form->field($model, 'password')->label(false)->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <!--<?= $form->field($model, 'rememberMe')->checkbox() ?>-->

        <div class="form-group">
            <?= Html::submitButton('Войти', ['class' => 'btn btn-primary block full-width m-b', 'name' => 'login-button']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
