<?php
	use yii\helpers\Html;
	use yii\widgets\Pjax;

	if (!empty($show_on_map))
	{
		$this->registerJsFile('https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=987cf952-38fd-46ee-b595-02977f1247ac',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
		$this->registerJsFile('/js/onmap.js',['depends'=>[\yii\web\JqueryAsset::className()],'position'=>\yii\web\View::POS_END]);
	}

	$i=1;
?>

<?=$this->render('_search',['unique_hash'=>$unique_hash,'search_columns'=>$search_columns,'pagesize'=>$pagesize])?>

<div class="collection-controls">
	<?php if (!empty($show_download) && !empty($setting)){?>
		<a href="/collection/download?key=<?=$setting->key?>&id_page=<?=$page->id_page?>">Скачать</a>
	<?php }?>
	<?php if (!empty($show_on_map)){?>
		<a class="showonmap" data-hash="<?=$unique_hash?>" data-id="<?=$id_collection?>" data-column="<?=$show_on_map?>" href="javascript:">Показать на карте</a>
	<?php }?>
</div>

<?php if (!empty($show_on_map)){?>
<div class="collection-map">
	<div id="map<?=$unique_hash?>"></div>
</div>
<?php }?>
<?php Pjax::begin([
	'id' => $unique_hash,
	'timeout'=>5000
	//'enablePushState' => false,
]) ?>
<?php
	if (!empty($table_style)){
		echo "<style>$table_style</style>";
	}
?>
<div class="table-responsive">
	<table>
		<thead>
			<?php if (!empty($table_head)){
				echo $table_head;
			}
else {
	$rowspan = 1;
	$tr_1 = [];
	$tr_2 = [];

	foreach ($columnsOptions as $key => $column)
	{
		if (!empty($column['group']))
		{
			$rowspan = 2;
			$tr_1[$column['group']] = [
				'label'=>$column['group'],
				'colspan'=>($tr_1[$column['group']]['colspan']??0)+1];

			$tr_2[] = $columns[$key];
		}
		else
			$tr_1[] = $columns[$key];
	}
?>
			<tr>
				<?php if ($show_row_num){?>
					<th <?=$rowspan>1?'rowspan="2"':''?> width="10"></th>
				<?php }?>
				<?php foreach ($tr_1 as $key => $column) {?>
					<th <?=($rowspan>1 && empty($column['colspan']))?'rowspan="2"':''?> <?=(!empty($column['colspan']))?'colspan="'.$column['colspan'].'"':''?>><?=(!empty($column['label']))?$column['label']:$column->name?></th>
				<?php }?>
			</tr>
			<?php if (!empty($tr_2)){?>
			<tr>
				<?php foreach ($tr_2 as $key => $column) {?>
					<th><?=$column->name?></th>
				<?php }?>
			</tr>
			<?php }?>

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
<?php }?>
		</thead>
		<tbody>
			<?php foreach ($allrows as $id_record => $row){?>
				<tr>
				<?php if ($show_row_num){?>
					<td class="row_num" width="10"><?=$offset+$i++?></td>
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
			<?php }?>
		</tbody>
	</table>
</div>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pagination,
    'nextPageLabel'=>'>',
    'lastPageLabel'=>'>>',
    'prevPageLabel'=>'<',
    'firstPageLabel'=>'<<'
]);?>
<?php Pjax::end(); ?>