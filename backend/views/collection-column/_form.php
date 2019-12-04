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

    <?= $form->field($model, 'name')->textInput(['disabled' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel(),['class'=>'form-control column-type','disabled' => true])
    ?>

    <?= $form->field($model, 'alias')->textInput(['disabled' => true]) ?>

    <?php
    $data = $model->getOptionsData();

	echo '<div class="row-flex">';

	foreach ($data as $key => $option)
	{
		$option['class'] = 'form-control';
		echo '<div class="col">
				<label class="control-label">'.$option['name'].'</label>';
				echo Html::textInput("CollectionColumn[options][$key]",$option['value'],$option);
		echo '</div>';
	}
	echo '</div>';
	?>

    <hr>

    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
