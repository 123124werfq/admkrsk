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