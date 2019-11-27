<?php 
    if (empty($news))
        return '';
    
?>
<div class="news-list">
    <?php if (!empty($tab['widenews'])){?>
    <div class="news-item news-item__wide news-item__wide-anons">
        <div class="news-item_holder">
            <h3 class="news_title"><a href="<?=$tab['widenews']->getUrl()?>"><?=$tab['widenews']->title?></a></h3>
            <?=$tab['widenews']->content?>
            <ul class="events_info">
                <?php if (!empty($tab['widenews']->id_rub)){?>
                <li class="events_info-item events_info-item__place"><a href="<?=$tab['widenews']->getUrl()?>?id_rub=<?=$tab['widenews']->id_rub?>"><?=$tab['widenews']->rub->getLineValue()?></a></li>
                <?php }?>
                <li class="events_info-item"><?=strftime('%d %B %Y, %H:%M', $tab['widenews']->date_publish)?></li>
            </ul>
        </div>
    </div>
    <?php }?>

    <?php foreach ($news as $key => $data)
        echo $this->render('_news',['data'=>$data,'page'=>$page]);
    ?>
</div>