<?php
	foreach ($allrows as $id_record => $row)
	{
		if (empty($model->template_element))
		{
			echo $this->render('_default_template_row',[
				'columns'=>$columns,
				'row'=>$row,
			]);
		}
		else
			echo $this->render('_custom_row',[
				'columns'=>$columns,
				'row'=>$row,
				'id_record'=>$id_record,
				'id_page'=>$page->id_page,
				'template'=>$model->template_element,
			]);
	}

	echo \yii\widgets\LinkPager::widget([
	    'pagination' => $pagination,
	]);
?>