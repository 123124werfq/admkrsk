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