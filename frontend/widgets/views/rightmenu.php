<?php
	$submenu = false;
	$siblings = false;

	$menu = $page->menu;

	if (empty($menu))
	{
		if (!empty($page->childs))
		{
			$submenu = $page->getChilds()->andWhere(['hidemenu'=>0])->all();
		}
		else if (!empty($page->parent->menu))
		{
			$menu = $page->parent->menu;
		}
		else if (!empty($page->parent->childs))
		{
			$siblings = true;
			$submenu = $page->parent->getChilds()->andWhere(['hidemenu'=>0])->all();
		}
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
		if (!empty($menu))
			foreach ($menu->getLinks()->where(['state'=>1])->all() as $key => $link)
			{
				$url = $link->getUrl();
?>
			<li <?=$url==Yii::$app->request->url?'class="selected active"':''?>><a href="<?=$url?>"><?=$link->label?></a></li>
<?php
		 	}
?>
	</ul>
</div>
