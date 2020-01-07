<?php
	use yii\helpers\Html;
	use yii\widgets\Pjax;

	$i=1;
?>
<form class="search-table" data-hash="<?=$unique_hash?>" action="" >
	<?php if (!empty($search_columns)){?>
		<?php foreach ($search_columns as $key => $column)
		{
			if ($column['type']==0)
				echo Html::dropDownList('search_column['.$unique_hash.']['.$column['column']->id_column.']','',$column['values'],['class'=>'form-control','prompt'=>$column['column']->name]);
			else
				echo Html::textInput('search_column['.$unique_hash.']['.$column['column']->id_column.']','',['class'=>'form-control','placeholder'=>$column['column']->name,'max-lenght'=>255]);
		 }?>
 	<?php }?>
 	<?=Html::dropDownList('ps','',[$pagesize=>$pagesize,20=>20,30=>30,50=>50],['class'=>'form-control pagesize']);?>
</form>
<?php Pjax::begin([
	'id' => $unique_hash,
	'timeout'=>5000
	//'enablePushState' => false,
]) ?>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
				<?php if ($show_row_num){?>
					<th width="10"></th>
				<?php }?>
			<?php foreach ($columns as $key => $column) {?>
				<th><?=$column->name?></th>
			<?php }?>
			</tr>

			<?php
			if (!empty($show_column_num))
			{
				if ($show_row_num)
					echo '<th width="10"></th>';

				$colnum = 1;
			 	foreach ($columns as $key => $column)
			 		echo '<th class="colnum">'.($colnum++).'</th>';
			}
			?>
		</thead>
		<tbody>
			<?php foreach ($allrows as $key => $row){?>
				<tr>
				<?php if ($show_row_num){?>
					<td class="row_num" width="10"><?=$offset+$i++?></td>
				<?php }?>
				<?php foreach ($columns as $key => $column) {?>
					<td><?php
						if (isset($row[$column->alias]))
						{
							if (is_array($row[$column->alias]))
								echo implode('<br>', $row[$column->alias]);
							else
								echo $row[$column->alias];

						}?>
					</td>
				<?php }?>
				</tr>
			<?php }?>
		</tbody>
	</table>
</div>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);?>
<?php Pjax::end(); ?>