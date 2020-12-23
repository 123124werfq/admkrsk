<?php
    use common\models\Page;
    /*$cover = (!empty($blockVars['cover']))?$blockVars['cover']->makeThumb(['w'=>1920,'h'=>1080]):'';
    $cover_mobile = (!empty($blockVars['cover_mobile']))?$blockVars['cover_mobile']->makeThumb(['w'=>1920,'h'=>1080]):'';*/

    $cover = (!empty($blockVars['cover']))?$blockVars['cover']->medias:'';// ?$blockVars['cover']
    $cover_mobile = (!empty($blockVars['cover_mobile']))?$blockVars['cover_mobile']->medias:'';
    $background = (!empty($blockVars['background']))?$blockVars['background']->value:0;
    $countdown = (!empty($blockVars['countdown']))?strtotime($blockVars['countdown']->value):0;
?>
<div class="main-slider_item main-slider_item__single <?=!empty($background)?'has-backgroud':''?>" style="background-image: url(<?=''//$cover?>);">
    <?php if (count($cover)>1){?>
    <div class="gid-slider hidden-accessability">
        <?php
            foreach ($cover as $key => $media) {?>
            <img src="<?=$media->showThumb(['w'=>1920,'h'=>1080])?>" alt=""/>
        <?php }?>
    </div>
    <?php }?>

    <?php if (count($cover)==1){?>
    <picture class="main-slider_img">
        <?php if (!empty($cover_mobile[0])){?>
        <source media="(max-width: 992px)" srcset="<?=$cover_mobile[0]->showThumb(['w'=>1280,'h'=>1024])?>?>">
        <?php }?>
        <img src="<?=$cover[0]->showThumb(['w'=>1920,'h'=>1080])?>" alt=""/>
    </picture>
    <?php }?>
    <div class="main-slider_content">
        <div class="container">
            <div class="main-slider_content-holder">
                <h1 class="main-slider_title"><?=(!empty($blockVars['title']))?$blockVars['title']->value:''?></h1>
                <p class="main-slider_text">
                    <?=(!empty($blockVars['content']))?$blockVars['content']->value:''?>
                </p>
                <?php if (!empty($blockVars['id_page']->value)){
                    $programm_page = Page::findOne((int)$blockVars['id_page']->value);
                ?>
                <a href="<?=$programm_page->getUrl()?>" class="btn btn__secondary">Посмотреть программу</a>
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