<?php use yii\helpers\Html;?>
<?php if (!empty($search_columns)){?>
	<div class="search-table">
	<?php foreach ($search_columns as $key => $column)
	{
		if ($column['type']==0)
			echo Html::dropDownList('search_column['.$column['column']->id_column.']','',$column['values'],['class'=>'form-control','prompt'=>$column['column']->name]);
		else
			echo Html::textInput('search_column['.$column->id_column.']','',['class'=>'form-control','placeholder'=>$column->name,'max-lenght'=>255]);
	 }?>
	</div>
<?php }?>
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