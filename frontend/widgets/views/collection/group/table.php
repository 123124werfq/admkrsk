<?php
	use yii\helpers\Html;
	use yii\widgets\Pjax;
?>
<?=$this->render('../_search',['unique_hash'=>$unique_hash,'search_columns'=>$search_columns,'pagesize'=>$pagesize])?>

<?php Pjax::begin([
	'id' => $unique_hash,
	'timeout'=>5000
	//'enablePushState' => false,
]) ?>
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

				foreach ($allrows as $id_record => $row){?>
				<tr>
					<?php if ($show_row_num){?>
						<td><?=$offset+$i++?></td>
					<?php }?>
					<?php foreach ($columns as $ckey => $column) {?>
						<td><?php
							if (isset($row[$column->alias]))
							{
								$value = $column->getValueByType($row[$column->alias]);

								if ($column->is_link)
									echo Html::a($value, ['/collection','id'=>$id_record,'id_page'=>$page->id_page,'id_collection'=>$id_collection]);
								elseif (!empty($columnsOptions[$column->alias]['filelink']) && !empty($row[$columnsOptions[$column->alias]['filelink']]))
									echo '<a href="'.$row[$columnsOptions[$column->alias]['filelink']].'" download>'.$value.'</a>';
								else
									echo $value;
							}?>
						</td>
					<?php }?>
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
<?php Pjax::end(); ?>