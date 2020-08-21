<form class="search-table" data-hash="<?=$unique_hash?>" action="">
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