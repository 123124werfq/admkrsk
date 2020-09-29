<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\CollectionColumn;
/* @var $this yii\web\View */
/* @var $model common\models\CollectionColumn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ibox">
    <div class="ibox-content">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['disabled' => !$model->isCustom()]) ?>

    <?= $form->field($model, 'type')->dropDownList(CollectionColumn::getTypeLabel(),['class'=>'form-control column-type','disabled' => true])
    ?>

    <?= $form->field($model, 'alias')->textInput(['disabled' => !$model->isCustom()]) ?>

	<?php if ($model->isCustom()){?>
	<?= $form->field($model, 'keep_relation')->checkBox()->hint('В случае измеенния даных в записи источнике, колонка будет переформирована')?>

	<div class="row">
		<div class="col-md-6">
			<?= $form->field($model, 'template')->textArea(['rows'=>10])->hint('Поддерживается синтаксис шаблонизаторв TWIG')?>
		</div>
		<div class="col-md-6">
		<table class="table">
			<?php foreach ($model->collection->columns as $key => $column)
			{
				$props = $column->getTemplateProperties();

				if (!empty($props))
					foreach ($props as $alias => $prop )
	            		echo '<tr><th width="100">' . ($alias) . '</th><td>' . $column->name.' '.$prop . '</td></tr>';
	        } ?>
	    </table>
	    </div>
	</div>
<?php }?>

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
</div>
