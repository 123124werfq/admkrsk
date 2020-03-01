<div class="table-responsive">
	<table class="<?=!empty($table_style)?$table_style:''?>">
		<thead>
<?php if (!empty($table_head))
				echo $table_head;
else {?>
			<tr>
			<?php if ($show_row_num){?>
				<th></th>
			<?php }?>
			<?php foreach ($columns as $key => $column) {?>
				<th><?=$column->name?></th>
			<?php }?>
			</tr>
<?php }?>
		</thead>
		<tbody>
			<?php
			$i = 1;
			foreach ($groups as $group => $allrows)
			{
				if (!empty($group))
					echo '<tr><td class="table-group" colspan="'.count($columns).'">'.$group.'</td></tr>';

				foreach ($allrows as $key => $row){?>
				<tr>
					<?php if ($show_row_num){?>
						<td><?=$offset+$i++?></td>
					<?php }?>
					<?php foreach ($row as $tkey => $td){
						echo "<td>$td</td>";
					}?>
				</tr>
		<?php   }
			}
		?>
		</tbody>
</table>
</div>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);?>