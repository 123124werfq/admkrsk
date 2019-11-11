<?php
	$i = 0;
	if (is_array($data))
	{
		foreach ($data as $rkey=>$record)
		{
			$class_name = get_class($record);
			echo '<div class="inline-inputs">';

			$i++;

			foreach ($fields as $key=>$field)
			{
				if (is_array($field) && isset($field['autocomplete']))
				{
					$inputID = $relation."_".$field['autocomplete'].$i;
					$field['params']['to'] = $field['autocomplete'];
					echo CHtml::activeHiddenField($record,"[$relation][$rkey]{$field['autocomplete']}",array('id'=>$inputID));
					echo CHtml::activeTextField($record,"[$relation][$rkey]$key",$field['params']);
				}
				else
				{
					if (is_numeric($field))
					{
						$record->$key = $field;
						echo CHtml::activeHiddenField($record,"[$relation][$rkey]$key");
					}
					else
					{
						if (isset($field['params']['type']) && $field['params']['type']=='hidden')
							echo CHtml::activeHiddenField($record,"[$relation][$rkey]$key");
						else 
							echo CHtml::activeTextField($record,"[$relation][$rkey]$key",$field['params']);
					}
				}

				// пробельчик ^_^
				echo ' ';
			}

			// закрывающий элемент
			if (!$this->single)
				echo '<a class="close inline" href="#" onclick="return removeInput(this)">&times;</a>';

			echo '</div>';
		}
	}
?>