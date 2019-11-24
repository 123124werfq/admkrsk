<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Collectionrecord */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collectionrecord-form">

    <?php $form = ActiveForm::begin(); ?>

    <?=\frontend\widgets\FormsWidget::widget(['form'=>$collection->form,'inputs'=>[
        'id_collection'=>$collection->id_collection,
        'id_record'=>$model->id_record,
    ]])?>

    <?php ActiveForm::end(); ?>

</div>
