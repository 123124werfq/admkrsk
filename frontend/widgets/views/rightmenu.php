<?php
	$submenu = false;
	$siblings = false;

	if (!empty($page->childs))
		$submenu = $page->childs;
	else if (!empty($page->parent->childs))
	{
		$siblings = true;
		$submenu = $page->parent->childs;
	}
?>

<div class="sidemenu">
	<ul>
		<?php if (!empty($submenu))
		{
			foreach ($submenu as $key => $data) {
?>
			<li <?=$data->id_page==$page->id_page?'class="selected active"':''?>><a href="<?=($siblings)?$data->alias:'/'.Yii::$app->request->getPathInfo().'/'.$data->alias?>"><?=$data->title?></a></li>
<?php
			}
		}
?>
<?php
		if (!empty($page->menu))
			foreach ($page->menu->links as $key => $link)
			{
?>
			<li><a href="<?=$link->getUrl()?>"><?=$link->label?></a></li>
<?php
		 	}
?>
	</ul>
</div>
