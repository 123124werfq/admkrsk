<?php
	$styles = $element->getStyles();
?>
<div data-id="<?=$element->id_element?>" class="form-element col" <?=(!empty($styles))?'style="'.implode(';',$styles).'"':''?>>
	<div class="input form-control <?=!empty($element->input->required)?'required':''?>" <?=(!empty($styles['font-size']))?'style="'.$styles['font-size'].'"':''?>>
		<?=$element->input->name?>:<?=$element->input->fieldname?> <?=!empty($element->input->typeOptions->esia)?'<span class="badge">ЕСИА</span>':''?>
	</div>
	<div class="btn-group">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
	        ...
	    </button>
	    <ul class="dropdown-menu">
	    	<?php if ($form->isMainForm()){?>
	    		<li><a class="update-input" href="/form-input/update?id=<?=$element->id_input?>">Редактировать</a></li>
	    	<?php }else {?>
	    		<li><a class="update-input" href="/form-element/update?id=<?=$element->id_element?>">Редактировать</a></li>
	    	<?php }?>
	    	<li><a href="/form-element/delete?id=<?=$element->id_element?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" >Удалить элемент</a></li>
	    </ul>
	</div>
</div>