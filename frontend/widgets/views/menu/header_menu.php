<ul class="header-menu_list">
<?php foreach ($menu->activeLinks as $key => $link) {?>
    <li class="header-menu_item <?=$link->getUrl()==Yii::$app->request->url?'active':''?>">
        <a href="<?=$link->getUrl()?>" class="header-menu_link"><?=$link->label?></a>
        <?php if (!empty($link->childs)){?>
        <ul class="header-submenu">
            <?php foreach ($link->childs as $key => $child) {?>
            <?php }?>
                <li class="header-submenu_item"><a href="<?=$child->getUrl()?>" class="header-submenu_link"><?=$child->label?></a></li>
        </ul>
        <?php }?>
    </li>
<?php }?>
</ul>