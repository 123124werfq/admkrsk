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

<div class="collection-controls">
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
<div class="table-responsive">
	<table <?=!empty($table_style)?'class="'.$table_style.'"':''?>>
		<thead>
			<?php if (!empty($table_head))
			{
				echo $table_head;
			}
else {?>
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
<?php }?>
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
    'nextPageLabel'=>'>',
    'lastPageLabel'=>'>>',
    'prevPageLabel'=>'<',
    'firstPageLabel'=>'<<'
]);?>
<?php Pjax::end(); ?>