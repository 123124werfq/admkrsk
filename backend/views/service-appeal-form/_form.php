<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ServiceAppealForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="service-appeal-form-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_form')->textInput() ?>

    <?= $form->field($model, 'id_record_firm')->textInput() ?>

    <?= $form->field($model, 'id_record_category')->textInput() ?>

    <?= $form->field($model, 'id_service')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
