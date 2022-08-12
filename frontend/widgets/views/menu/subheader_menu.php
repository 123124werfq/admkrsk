<ul class="sitemap_menu">
    <!-- элементам с подменю добавлять класс sitemap_menu-item__submenu -->
    <?php foreach ($menu->getLinks()->with('activeChilds')->where('id_parent IS NULL')->all() as $key => $link) {?>
    <li class="sitemap_menu-item <?=!empty($link->activeChilds)?'sitemap_menu-item__submenu':''?>">
        <span class="sitemap_header-wrap">
            <a href="<?=$link->getUrl()?>" class="sitemap_header"><span class="sitemap_header-text"><?=$link->label?></span></a>
        </span>
        <?php if (!empty($link->activeChilds)){?>
        <ul class="sitemap_submenu">
            <?php foreach ($link->activeChilds as $key => $child) {?>
            <li class="sitemap_submenu-item"><a href="<?=$child->getUrl()?>" class="sitemap_submenu-link"><?=$child->label?></a></li>
            <?php }?>
        </ul>
        <?php }?>
    </li>
    <?php }?>
</ul>