<?php
    use yii\helpers\Html;
    $links = $menu->getLinks()->where('id_parent IS NULL')->all();
?>
<div class="goslinks tab-container">
    <div class="container">
        <div class="custom-header">
            <div class="custom-header-left">
                <h2>Полезные ссылки</h2>
            </div>
            <div class="custom-header-right">
                <div class="smart-menu-tabs smart-menu-tabs__right slide-hover tab-controls tab-controls__invert tab-controls__responsive tab-controls__filter">
                    <div class="tab-controls-holder">
                        <span class="slide-hover-line"></span>
                        <div class="smart-menu-tabs_item tab-control tab-control__active slide-hover-item" data-href="0"><a  class="smart-menu-tabs_control">Все</a></div>
                        <?php foreach ($links as $key => $link){?>
                        <div class="smart-menu-tabs_item tab-control slide-hover-item" data-href="<?=$link->id_link?>"><a class="smart-menu-tabs_control"><?=Html::encode($link->label)?></a></div>
                        <?php }?>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-content goslinks-list">
            <?php
                foreach ($links as $key => $link)
                    foreach ($link->childs as $key => $child) {?>
                        <div class="goslinks-col" data-filter-type="<?=$link->id_link?>">
                            <a href="<?=$child->getUrl()?>" class="sponsor" target="_blank">
                                <img class="sponsor_img" src="<?=$child->makeThumb(['w'=>370,'h'=>160])?>" alt="<?=Html::encode($child->label)?>">
                            </a>
                        </div>
            <?php } ?>
        </div>
        <button class="load-more-block btn btn__block show-hidden" data-show-target="#hidden-goslinks">Показать еще</button>
    </div>
</div>