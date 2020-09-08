<?php
    use common\models\Menu;
?>
<div class="gid">
    <div class="gid-slider hidden-accessability">
        <?php
            if (!empty($blockVars['medias']))
            foreach ($blockVars['medias']->medias as $key => $media) {?>
            <img src="<?=$media->getUrl()?>" alt=""/>
        <?php }?>
    </div>
    <div class="gid-content">
        <div class="container">
            <div class="row">
                <div class="col-2-third">
                    <h2 class="gid_title"><?=(!empty($blockVars['title']))?$blockVars['title']->value:''?></h2>
                </div>
                <?php if (!empty($blockVars['menu']->value)){?>
                <div class="col-third">
                    <ul class="gid-menu">
                            <?php
                            $menu = Menu::findOne($blockVars['menu']->value);

                            if (!empty($menu))
                                foreach ($menu->links as $key => $link) {?>
                                <li class="gid-menu_item"><a href="<?=(!empty($link->id_page))?$link->page->getUrl():$link->url?>" class="gid-menu_link"><?=$link->label?></a></li>
                        <?php }?>
                    </ul>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>