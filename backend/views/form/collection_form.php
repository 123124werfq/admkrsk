<?php
	$arrayGroup = md5(rand(0,10000).time());

	echo \frontend\widgets\FormsWidget::widget([
		'form'=>$input->collection->form,
		'arrayGroup'=>$arrayGroup,
		'activeForm'=>$form,
		'inputs'=>["input".$input->id_input.'[]'=>$arrayGroup],
		'template'=>'form_in_form',
	]);
?>