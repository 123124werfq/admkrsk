<div>
    <a href="#"><?=$data->name?> / <?=$data->getServices()->count()?></a>
    <div class="button-column">
        <a href="service-situation/create?&id_parent=<?=$data->id_situation?>" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
        <a href="service-situation/update?id=<?=$data->id_situation?>" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
        <a href="service-situation/delete?id=<?=$data->id_situation?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
    </div>
</div>