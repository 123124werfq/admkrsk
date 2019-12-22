<?php 
	use yii\helpers\Html;
	use yii\widgets\Pjax;
?>
<?php if (!empty($search_columns)){?>
	<form class="search-table" data-hash="<?=$unique_hash?>" action="" >
	<?php foreach ($search_columns as $key => $column)
	{
		if ($column['type']==0)
			echo Html::dropDownList('search_column['.$unique_hash.']['.$column['column']->id_column.']','',$column['values'],['class'=>'form-control','prompt'=>$column['column']->name]);
		else
			echo Html::textInput('search_column['.$unique_hash.']['.$column['column']->id_column.']','',['class'=>'form-control','placeholder'=>$column['column']->name,'max-lenght'=>255]);
	 }?>
	 </form>
<?php }?>
<?php Pjax::begin([
	'id' => $unique_hash,
	//'enablePushState' => false,
]) ?>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
			<?php foreach ($columns as $key => $column) {?>
				<th><?=$column->name?></th>
			<?php }?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($allrows as $key => $row){?>
				<tr>
				<?php foreach ($columns as $key => $column) {?>
					<td><?=$row[$column->alias]??''?></td>
				<?php }?>
				</tr>
			<?php }?>
		</tbody>
</table>
	<?=\yii\widgets\LinkPager::widget([
	    'pagination' => $pagination,
	]);?>
</div>
<?php Pjax::end(); ?>