<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\FirmUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'id_record')->textInput(['value'=>$model->record->label,'disabled'=>true]) ?>

        <?= $form->field($model, 'id_user')->textInput(['value'=>$model->user->username,'disabled'=>true]) ?>

        <?= $form->field($model, 'state')->dropDownList($model->getStateLabels()) ?>

        <hr>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
    </div>
</div>
