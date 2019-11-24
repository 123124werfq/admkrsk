<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CollectionColumn;
/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collection-column-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel(),['class'=>'form-control column-type'])
    ?>

    <?= $form->field($model, 'variables')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
