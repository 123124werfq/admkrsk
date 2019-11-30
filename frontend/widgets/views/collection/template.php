<?php
	foreach ($allrows as $key => $row)
	{
		if (empty($model->template_element))
		{
			echo $this->render('_default_template_row',[
				'columns'=>$columns,
				'row'=>$row,
			])
		}
		else
			echo 'Кастомный шаблон';
	}
?>