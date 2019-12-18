<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\ServiceAppealFormSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-appeal-form-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id_appeal') ?>

    <?= $form->field($model, 'id_form') ?>

    <?= $form->field($model, 'id_record_firm') ?>

    <?= $form->field($model, 'id_record_category') ?>

    <?= $form->field($model, 'id_service') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
