<li data-id="<?=$data->id_rub?>" class="menu-item">
    <div>
	    <a href="#"><?=$data->name?> / <?=$data->getServices()->count()?></a>
	    <div class="button-column">
	        <a href="/service-rubric/create?&id_parent=<?=$data->id_rub?>" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
	        <a href="/service-rubric/update?id=<?=$data->id_rub?>" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
	        <a href="/service-rubric/delete?id=<?=$data->id_rub?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
	    </div>
	</div>
    <ul data-id="<?=$data->id_rub?>" class="menu-childs">
    	<?php foreach ($data->childs as $ckey => $child)
    	{
    		echo $this->render('_row',['data'=>$child]);
    	}?>
    </ul>
</li>