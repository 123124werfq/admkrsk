
<?php
	$subArrayGroup = md5(rand(0,10000).time());

	$inputName = (!empty($arrayGroup))?"FormDynamic[$arrayGroup][input".$input->id_input.'][]':"FormDynamic[input".$input->id_input.'][]"';

	echo \frontend\widgets\FormsWidget::widget([
		'form'=>$input->collection->form,
		'arrayGroup'=>$subArrayGroup,
		'activeForm'=>$form,
		'inputs'=>[$inputName=>$subArrayGroup],
		'template'=>'form_in_form',
	]);
?>