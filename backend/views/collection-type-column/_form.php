<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CollectionColumn;
/* @var $this yii\web\View */
/* @var $model common\models\CollectionTypeColumn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel(),['class'=>'form-control column-type'])?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
    </div>
</div>
