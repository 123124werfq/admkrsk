<?php
    $cover = (!empty($blockVars['cover']))?$blockVars['cover']->makeThumb(['w'=>1920,'h'=>1080]):'';
    $cover_mobile = (!empty($blockVars['cover_mobile']))?$blockVars['cover_mobile']->makeThumb(['w'=>1920,'h'=>1080]):'';

    $countdown = (!empty($blockVars['countdown']))?strtotime($blockVars['countdown']->value):0;
?>
<div class="main-slider_item main-slider_item__single" style="background-image: url(<?=$cover?>);">
    <picture class="main-slider_img">
        <source media="(max-width: 992px)" srcset="<?=$cover_mobile?>">
        <img src="<?=$cover?>" alt=""/>
    </picture>
    <div class="main-slider_content">
        <div class="container">
            <div class="main-slider_content-holder">
                <h1 class="main-slider_title"><?=(!empty($blockVars['title']))?$blockVars['title']->value:''?></h1>
                <p class="main-slider_text">
                    <?=(!empty($blockVars['content']))?$blockVars['content']->value:''?>
                </p>
                <?php if (!empty($blockVars['programm'])){?>
                <a href="/event/program?id=<?=$blockVars['programm']->value?>&id_page=<?=$blockVars['id_page']->value??$page->id_page?>" class="btn btn__secondary">Посмотреть программу</a>
                <?php }?>
                <?php if (!empty($countdown) && $countdown>time()){?>
                <div class="main-countdown-holder">
                    <h4><?=(!empty($blockVars['countdown_title']))?$blockVars['countdown_title']->value:''?></h4>
                    <div class="main-countdown" data-date="<?=date('Y/m/d H:i',$countdown)?>"></div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>