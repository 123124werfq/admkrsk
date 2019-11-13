<div data-id="<?=$element->id_element?>" class="form-element col">
	<?=$element->content?>
	<div class="btn-group">
	    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
	        ...
	    </button>
	    <ul class="dropdown-menu">
	    	<li><a class="update-input" href="/form-element/update?id=<?=$element->id_input?>">Редактировать</a></li>
	    	<li><a href="/form-elemtn/delete?id=<?=$element->id_input?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post" >Удалить поле</a></li>
	    </ul>
	</div>
</div>