<?php

/* @var $page Page */

use common\models\Page;

	$submenu = false;

	if (!empty($submenu = $page->getChilds()->andWhere(['hidemenu'=>0])->orderBy('ord ASC')->all()))
	{
	}
	else if (!empty($submenu = $page->parent->getChilds()->andWhere(['hidemenu'=>0])->orderBy('ord ASC')->all()))
	{
	}
	else
	{

	}
?>

<div class="sidemenu">
	<ul>
		<?php if (!empty($submenu))
		{
			foreach ($submenu as $key => $data) {
?>
			<li <?=$data->id_page==$page->id_page?'class="selected active"':''?>><a href="<?=$data->getUrl()?>"><?=$data->label?:$data->title?></a></li>
<?php
			}
		}
?>
<?php
/*		if (!empty($menu))
			foreach ($menu->activeLinks as $key => $link)
			{
				$url = $link->getUrl();
?>
			<li <?=$url==Yii::$app->request->url?'class="selected active"':''?>><a href="<?=$url?>"><?=(!empty($link->id_page))?$link->page->getLabel():$link->label?></a></li>
<?php
		 	}*/
?>
	</ul>
</div>
