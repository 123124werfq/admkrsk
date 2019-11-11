<?php 
	use common\models\CollectionColumn;
	use yii\helpers\Html;

	$options = CollectionColumn::getTypeOptions($type->type);

	if (!empty($options))
	{
		echo '<div class="row-flex">';
		foreach ($options as $key => $option)
		{
			$inputOption = ['class'=>'form-control'];
			echo '<div class="col">
					<label class="control-label">'.$option['name'].'</label>';

			switch ($option['type']) 
			{
				case 'input':
					echo Html::textInput("options[$key]",'',$inputOption);					
					break;
				case 'dropdown':
					echo Html::dropDownList("options[$key]",'',$option['values'],$inputOption);
					break;

				default:
					# code...
					break;
			}
			echo '</div>';
		}
		echo '</div>';
	}
?>