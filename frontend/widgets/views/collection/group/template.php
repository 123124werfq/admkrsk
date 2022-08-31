<?php use yii\widgets\Pjax;?>

<?=$this->render('../_search',[
	'unique_hash'=>$unique_hash,
	'search_columns'=>$search_columns,
	'pagesize'=>$pagesize,
	'show_download'=>$show_download,
	'setting'=>$setting,
	'id_collection'=>$id_collection,
])?>

<?php Pjax::begin([
	'id' => $unique_hash,
	'timeout'=>5000
]) ?>

<?php
	foreach ($groups as $group => $allrows)
	{
		if (!empty($group))
			echo '<h3>'.$group.'</h3>';

		foreach ($allrows as $id_record => $row)
		{
			if (empty($model->template_element))
			{
				echo $this->render('../_default_template_row',[
					'columns'=>$columns,
					'row'=>$row,
				]);
			}
			else
				echo $this->render('../_custom_row',[
					'columns'=>$columns,
					'row'=>$row,
					'id_record'=>$id_record,
					'id_page'=>$page->id_page,
					'template'=>$model->template_element,
				]);
		}
	}

	echo \yii\widgets\LinkPager::widget([
	    'pagination' => $pagination,
	]);
?>

<?php Pjax::end(); ?>