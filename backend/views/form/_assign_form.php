<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\FormElement */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
	'id'=>'SubformForm'
]); ?>

<?= $form->field($model, 'id_form')->dropDownList(ArrayHelper::map($forms, 'id_form', 'name')) ?>

<?= $form->field($model, 'prefix')->input(['maxlength' => 255]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); ?>