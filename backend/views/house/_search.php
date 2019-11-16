<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\FiasHouseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="house-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'buildnum') ?>

    <?= $form->field($model, 'enddate') ?>

    <?= $form->field($model, 'housenum') ?>

    <?= $form->field($model, 'ifnsfl') ?>

    <?= $form->field($model, 'ifnsul') ?>

    <?php // echo $form->field($model, 'okato') ?>

    <?php // echo $form->field($model, 'oktmo') ?>

    <?php // echo $form->field($model, 'postalcode') ?>

    <?php // echo $form->field($model, 'startdate') ?>

    <?php // echo $form->field($model, 'strucnum') ?>

    <?php // echo $form->field($model, 'terrifnsfl') ?>

    <?php // echo $form->field($model, 'terrifnsul') ?>

    <?php // echo $form->field($model, 'updatedate') ?>

    <?php // echo $form->field($model, 'cadnum') ?>

    <?php // echo $form->field($model, 'eststatus') ?>

    <?php // echo $form->field($model, 'statstatus') ?>

    <?php // echo $form->field($model, 'strstatus') ?>

    <?php // echo $form->field($model, 'counter') ?>

    <?php // echo $form->field($model, 'divtype') ?>

    <?php // echo $form->field($model, 'aoguid') ?>

    <?php // echo $form->field($model, 'houseguid') ?>

    <?php // echo $form->field($model, 'houseid') ?>

    <?php // echo $form->field($model, 'normdoc') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
