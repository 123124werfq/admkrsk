<?php
	$styles = $element->getStyles();
?>
<div data-id="<?=$element->id_element?>" class="form-element col" <?=(!empty($styles))?'style="'.implode(';',$styles).'"':''?>>
	<div class="input form-control" <?=(!empty($styles['font-size']))?'style="'.$styles['font-size'].'"':''?>><?=$element->input->name?></div>
	<div class="btn-group">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
	        ...
	    </button>
	    <ul class="dropdown-menu">
	    	<li><a class="update-input" href="/form-input/update?id=<?=$element->id_input?>">Редактировать</a></li>
	    	<li><a href="/form-input/delete?id=<?=$element->id_input?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" >Удалить элемент</a></li>
	    </ul>
	</div>
</div>