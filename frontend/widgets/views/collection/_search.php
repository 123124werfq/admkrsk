<?php
	use yii\helpers\Html;
?>
<form class="search-table" data-hash="<?=$unique_hash?>" action="">
	<?php if (!empty($search_columns)){?>
		<?php foreach ($search_columns as $key => $column)
		{
			$name = 'search_column['.$unique_hash.']['.$column['column']->id_column.']';
			if ($column['type']==0)
				echo Html::dropDownList($name,'',$column['values'],['class'=>'form-control','prompt'=>$column['column']->name]);
			elseif ($column['type']==3)
				echo '
                        <div class="datepicker-holder">
                            <input name="'.$name.'" value="" type="text" class="form-control form-control_datepicker mb-sm-all-0 datepicker-ajax" placeholder="'.Yii::t('site', 'Показать за период').'">
                            <button class="form-control-reset material-icons" type="button">clear</button>
                        </div>
                      ';
			else
				echo Html::textInput($name,'',['class'=>'form-control','placeholder'=>$column['column']->name,'max-lenght'=>255]);
		 }?>
 	<?php }?>
 	<?=Html::dropDownList('ps','',[$pagesize=>$pagesize,20=>20,30=>30,50=>50],['class'=>'form-control pagesize']);?>
</form>

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