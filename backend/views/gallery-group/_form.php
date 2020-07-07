<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\GalleryGroup */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <hr>
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

    </div>
</div>
