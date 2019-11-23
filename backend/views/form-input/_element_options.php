<?php
	use yii\helpers\Html;

	$data = $element->getOptionsData();

	echo '<div class="row-flex">';

	foreach ($data as $key => $option)
	{
		$option['class'] = 'form-control';
		echo '<div class="col">
				<label class="control-label">'.$option['name'].'</label>';
				echo Html::textInput("FormElement[options][$key]",$option['value'],$option);
		echo '</div>';
	}
	echo '</div>';
?>