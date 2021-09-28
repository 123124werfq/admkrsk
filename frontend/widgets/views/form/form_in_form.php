<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use common\models\FormVisibleInput;

	$visibleElements = [];
	$visibleInputs = [];

	$visibleInputsModels = FormVisibleInput::find()->joinWith(['visibleInput'])->where(['id_form'=>$form->id_form])->all();

	foreach ($visibleInputsModels as $vkey => $vinput)
	{
		$visibleInputs[$vinput->id_input_visible][$vinput->id_element] = $vinput->id_element;
		$visibleElements[$vinput->id_element][$vinput->id_input_visible] = $vinput->values;
	}
?>

<?php if (!empty($visibleInputs)){

$visibleInputs = json_encode($visibleInputs);
$visibleElements = json_encode($visibleElements);
$script = <<< JS
$(document).ready(function() {
	let visibleInputs = $visibleInputs;
	let visibleElements = $visibleElements;
	visibleForm(visibleInputs,visibleElements,'#$arrayGroup');
});
JS;
$this->registerJs($script, yii\web\View::POS_END);
}?>

<div id="<?=$arrayGroup?>" class="subform">
	<?php foreach ($inputs as $name => $value)
			echo Html::hiddenInput($name,$value);
	?>
	<?=$this->render('_rows',['rows'=>$form->rows,'model'=>$model,'activeForm'=>$activeForm,'arrayGroup'=>$arrayGroup,'modelForm'=>$form])?>

	<div class="subform-action-buttons">
		<a class="btn btn-default btn__secondary delete-subform" href="javascript:">Удалить</a>
	</div>
</div>