<?php
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;

	$this->registerJsFile('/js/dropzone/dropzone.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	$this->registerCssFile('/js/dropzone/dropzone.min.css');

	$visibleField = [];
	$visibleSourceField = [];

	foreach ($form->rows as $key => $row)
		foreach ($row->elements as $ekey => $element)
		{
			if (!empty($element->id_input) && !empty($element->input))
			{
				if (!empty($element->input->visibleInputs))
				{
					foreach ($element->input->visibleInputs as $vkey => $vinput)
					{
						$visibleField[$vinput->id_input_visible][$element->input->id_input] = $vinput->values;
						$visibleSourceField[$element->input->id_input][$vinput->id_input_visible] = $vinput->values;
					}
				}
			}
		}
?>
<div class="boxed form-inside">
	<?php if (!empty($visibleField)){?>
	<script>
		var visibleInput = <?=json_encode($visibleField)?>;
		var visibleSourceInput = <?=json_encode($visibleSourceField)?>;

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


	<?php foreach ($form->rows as $key => $row)
	{
		echo '<div class="row">';
		foreach ($row->elements as $ekey => $element)
		{
			if (!empty($element->id_input))
				echo $this->render('_input',['input'=>$element->input,'element'=>$element,'model'=>$model,'form'=>$activeForm]);
			elseif (!empty($element->content))
			{
				$styles = $element->getStyles();
				echo '<div class="text-row" '.((!empty($styles))?'style="'.implode(';',$styles).'"':'').'>'.$element->content.'</div>';
			}
		}
		echo '</div>';
	}
	?>
	<div class="form-end">
        <div class="form-end_right">
            <input type="submit" class="btn btn__secondary" value="Отправить">
        </div>
    </div>
	<?php ActiveForm::end(); ?>
</div>