<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Notify */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="notify-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'message')->textarea()->label('Сообщение') ?>

    <?= $form->field($model, 'main_notify')->radioList(
        [
            1 => 'чем 30 минут',
            2 => 'чем 1 час',
            3 => 'чем 3 час',
        ],
        [
            'separator' => '&nbsp;&nbsp;&nbsp;</br>',
        ])->label('Получать уведомления не чаще') ?>

    <?= $form->field($model, 'repeat_notify')->radioList(
        [
            1 => 'чем 30 минут',
            2 => 'чем 1 час',
            3 => 'чем 3 час',
        ],
        [
            'separator' => '&nbsp;&nbsp;&nbsp;</br>',
        ])->label('Получать уведомления, для повторных изменений, не чаще') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
