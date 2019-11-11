<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\AddrObjSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="address-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'areacode') ?>

    <?= $form->field($model, 'autocode') ?>

    <?= $form->field($model, 'citycode') ?>

    <?= $form->field($model, 'code') ?>

    <?= $form->field($model, 'enddate') ?>

    <?php // echo $form->field($model, 'formalname') ?>

    <?php // echo $form->field($model, 'ifnsfl') ?>

    <?php // echo $form->field($model, 'ifnsul') ?>

    <?php // echo $form->field($model, 'offname') ?>

    <?php // echo $form->field($model, 'okato') ?>

    <?php // echo $form->field($model, 'oktmo') ?>

    <?php // echo $form->field($model, 'placecode') ?>

    <?php // echo $form->field($model, 'plaincode') ?>

    <?php // echo $form->field($model, 'postalcode') ?>

    <?php // echo $form->field($model, 'regioncode') ?>

    <?php // echo $form->field($model, 'shortname') ?>

    <?php // echo $form->field($model, 'startdate') ?>

    <?php // echo $form->field($model, 'streetcode') ?>

    <?php // echo $form->field($model, 'terrifnsfl') ?>

    <?php // echo $form->field($model, 'terrifnsul') ?>

    <?php // echo $form->field($model, 'updatedate') ?>

    <?php // echo $form->field($model, 'ctarcode') ?>

    <?php // echo $form->field($model, 'extrcode') ?>

    <?php // echo $form->field($model, 'sextcode') ?>

    <?php // echo $form->field($model, 'plancode') ?>

    <?php // echo $form->field($model, 'cadnum') ?>

    <?php // echo $form->field($model, 'divtype') ?>

    <?php // echo $form->field($model, 'actstatus') ?>

    <?php // echo $form->field($model, 'aoguid') ?>

    <?php // echo $form->field($model, 'aoid') ?>

    <?php // echo $form->field($model, 'aolevel') ?>

    <?php // echo $form->field($model, 'centstatus') ?>

    <?php // echo $form->field($model, 'currstatus') ?>

    <?php // echo $form->field($model, 'livestatus') ?>

    <?php // echo $form->field($model, 'nextid') ?>

    <?php // echo $form->field($model, 'normdoc') ?>

    <?php // echo $form->field($model, 'operstatus') ?>

    <?php // echo $form->field($model, 'parentguid') ?>

    <?php // echo $form->field($model, 'previd') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
