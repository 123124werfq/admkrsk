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
			<?php
			foreach ($groups as $group => $allrows)
			{
				if (!empty($group))
					echo '<tr><th colspan="'.count($columns).'">'.$group.'</th></tr>';

			foreach ($allrows as $key => $row){
?>
				<tr>
				<?php foreach ($row as $tkey => $td){
					echo "<td>$td</td>";
				}?>
				</tr>
		<?php }
			}
		?>
		</tbody>
</table>
</div>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
]);?>