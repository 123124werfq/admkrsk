<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \backend\models\search\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">
    <div class="row">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

        <?php // $form->field($model, 'id') ?>
        <div class="col-md-2">
            <?= $form->field($model, 'username') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'email') ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'source')->dropDownlist(
                [
                    0 => 'Все',
                    1 => 'ЕСИА',
                    2 => 'Active Directory',
                    3 => 'ЕСИА - Active Directory'
                ]) ?>
        </div>

    <?php // $form->field($model, 'password_hash') ?>

    <?php // $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'verification_token') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'id_esia_user') ?>

    <?php // echo $form->field($model, 'id_ad_user') ?>

    <?php // echo $form->field($model, 'fullname') ?>

    <div class="form-group">
        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
