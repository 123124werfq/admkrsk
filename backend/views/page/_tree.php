<div class="treerow col-sm-offset-<?=$offset?>">
	<div class="row">
		<div class="col-sm-10">
			<?=$data->title?> <a href="<?=$data->getUrl(true)?>" target="_blank"><?=$data->getUrl()?></a>
		</div>
		<div class="col-sm-2 text-right">
			<div class="button-column">
				<a href="create?id_page=<?=$data->id_page?>" title="Добавить" aria-label="Добавить"><span class="glyphicon glyphicon-plus"></span></a>
                <a href="update?id=<?=$data->id_page?>" title="Редактировать" aria-label="Редактировать"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="delete?id=<?=$data->id_page?>" title="Удалить" aria-label="Удалить" data-pjax="0" data-confirm="Вы уверены, что хотите удалить этот элемент?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a>
            </div>
		</div>
	</div>
</div>
<?php if (!empty($tree[$data->id_page]))
	foreach ($tree[$data->id_page] as $key => $leaf) {
		echo $this->render('_tree',['data'=>$leaf,'tree'=>$tree,'offset'=>$offset+1]);
	}
?>