<?php 
	use common\models\CollectionColumn;
	use yii\helpers\Html;

	$options = CollectionColumn::getTypeOptions($model->type);

	if (!empty($options))
	{
		echo '<div class="row-flex">';
		
		foreach ($options as $key => $option)
		{
			$inputOption = ['class'=>'form-control'];
			echo '<div class="col">
					<label class="control-label">'.$option['name'].'</label>';

			$value = (isset($model->options[$key]))?$model->options[$key]:'';
			
			switch ($option['type']) 
			{
				case 'input':
					echo Html::textInput("FormInput[options][$key]",$value,$inputOption);					
					break;
				case 'dropdown':
					echo Html::dropDownList("FormInput[options][$key]",$value,$option['values'],$inputOption);
					break;
				default:
					break;
			}
			echo '</div>';
		}
		echo '</div>';
	}
?>