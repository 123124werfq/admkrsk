<?php
	$styles = $element->getStyles();
?>
<div data-id="<?=$element->id_element?>" class="form-element col" <?=(!empty($styles))?'style="'.implode(';',$styles).'"':''?>>
	<?=$this->render('_form_view',['rows'=>$element->subForm->rows])?>
	<div class="btn-group">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
	        ...
	    </button>
	    <ul class="dropdown-menu">
	    	<li><a class="update-input" href="/form-element/update?id=<?=$element->id_element?>">Редактировать</a></li>
	    	<li><a href="/form-element/delete?id=<?=$element->id_element?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" >Удалить элемент</a></li>
	    </ul>
	</div>
</div>