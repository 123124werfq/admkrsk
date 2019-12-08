<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use common\models\FormVisibleInput;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerCssFile('/js/dropzone/dropzone.min.css');

	$visibleElements = [];
	$visibleInputs = [];

	$visibleInputs = FormVisibleInput::find()->joinWith(['visibleInput'])->where(['id_form'=>$form->id_form])->all();

	foreach ($visibleInputs as $vkey => $vinput)
	{
		$visibleInputs[$vinput->id_input_visible][$vinput->id_element] = $vinput->id_element;

		$visibleElements[$vinput->id_element][$vinput->id_input_visible] = $vinput->values;
	}
?>
<div class="boxed form-inside">
	<?php if (!empty($visibleInputs)){?>
	<script>
		var visibleInputs = <?=json_encode($visibleInputs)?>;
		var visibleElements = <?=json_encode($visibleElements)?>;
	</script>
	<?php }?>

	<?php $activeForm = ActiveForm::begin([
		'action'=>($action===null)?'/form/create?id='.$form->id_form:$action,
		'fieldConfig' => [
	        'template' => '{input}{error}',
	    ],
		'options'=>[
			'enctype'=>'multipart/form-data'
		]
	]); ?>

	<?php foreach ($inputs as $name => $value)
			echo Html::hiddenInput($name,$value);
	?>

	<?=$this->render('_rows',['rows'=>$form->rows,'model'=>$model,'activeForm'=>$activeForm])?>
	<div class="form-end">
        <div class="form-end_right">
            <input type="submit" class="btn btn__secondary" value="Отправить">
        </div>
    </div>
	<?php ActiveForm::end(); ?>
</div>