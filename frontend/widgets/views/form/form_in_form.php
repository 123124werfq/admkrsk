<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use common\models\FormVisibleInput;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerCssFile('/js/dropzone/dropzone.min.css');

	$visibleElements = [];
	$visibleInputs = [];

	/*$visibleInputs = FormVisibleInput::find()->joinWith(['visibleInput'])->where(['id_form'=>$form->id_form])->all();

	foreach ($visibleInputs as $vkey => $vinput)
	{
		$visibleInputs[$vinput->id_input_visible][$vinput->id_element] = $vinput->id_element;
		$visibleElements[$vinput->id_element][$vinput->id_input_visible] = $vinput->values;
	}*/
?>

<?php /*if (!empty($visibleInputs)){?>
<script>
	var visibleInputs = <?=json_encode($visibleInputs)?>;
	var visibleElements = <?=json_encode($visibleElements)?>;
</script>
<?php }*/?>

<div id="<?=$arrayGroup?>" class="subform">
	<?php foreach ($inputs as $name => $value)
			echo Html::hiddenInput($name,$value);
	?>
	<?=$this->render('_rows',['rows'=>$form->rows,'model'=>$model,'activeForm'=>$activeForm,'arrayGroup'=>$arrayGroup])?>

	<div class="subform-action-buttons">
		<a class="btn btn__secondary delete-subform" href="javascript:">Удалить</a>
	</div>
</div>